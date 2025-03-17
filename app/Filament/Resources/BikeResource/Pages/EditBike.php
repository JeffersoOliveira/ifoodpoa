<?php

namespace App\Filament\Resources\BikeResource\Pages;

use App\Enums\BikeStatusEnum;
use App\Enums\MaintenanceStatusEnum;
use App\Filament\Resources\BikeResource;
use App\Models\ChecklistItem;
use App\Models\Maintenance;
use Auth;
use Carbon\Carbon;
use DB;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBike extends EditRecord
{
    protected static string $resource = BikeResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function mutateFormDataBeforeSave(array $data): array
    {
        if (isset($data['itemsFaltaPeças']) && is_array($data['itemsFaltaPeças'])) {

            $options = [
                "freio",
                "guidao",
                "quadro",
                "rodas",
                "sinalizaçao",
                "sistemaEletrico",
                "transmissao"
            ];

            // Inicializa o array com todas as opções como false
            $checkList = array_fill_keys($options, false);


            foreach ($data['itemsFaltaPeças'] as $index => $item) {
                if (!empty($data['itemsFaltaPeças'][$index]['check_list'])) {
                    $checkList[$item['check_list']] = true;
                }
            }

            // Adiciona status anterior
            $checkList['statusAnterior'] = BikeStatusEnum::AVAILABLE;

            // Agrupa os nomes das peças ausentes
            $checkList['pecasFaltando'] = array_reduce($data['itemsFaltaPeças'], function ($carry, $item) {
                $carry[$item['check_list']][] = $item['name'];
                return $carry;
            }, []);

            // Criação da manutenção
                        $maintenance = Maintenance::create([
                'bike_id' => $this->record->id,
                'attendant_id' => Auth::user()->id,
                'maintenance_date' => Carbon::now()->toDateString(),
                'maintenance_time' => Carbon::now()->setTime(9, 0, 0)->toTimeString(),
                'status' => MaintenanceStatusEnum::PENDING->value,
                'type' => 'corrective',
            ]);

            // Criação do CheckList
            $checkList = new ChecklistItem([
                'check_list' => $checkList,
                'description' => 'Manutenção de Falta de Peça(s)',
            ]);

            // Associa o checklist à manutenção
            $maintenance->checkList()->save($checkList);
            $this->record->status = BikeStatusEnum::MISSING_PARTS->value;
            $this->record->save();

            // Debugging
            // dd($this->record->id, Auth::user()->id, $data, json_encode($checkList['pecasFaltando']), json_encode($checkList));


            // }

            // Criação do CheckList


            //     // Commit da transação
            // DB::commit();

            //     return response()->json(['success' => 'Manutenção e Checklist criados com sucesso!']);
            // } catch (\Exception $e) {
            //     // Rollback da transação em caso de erro
            //     DB::rollBack();
            //     return response()->json(['error' => $e->getMessage()], 500);
            // }
        }

        return $data;
    }
}
