<?php

declare(strict_types=1);

namespace App\Http\Handlers\Device;

use App\Facades\ObadaClient;
use App\Models\Device;
use App\Models\Documents;
use App\Models\Metadata;
use App\Models\StructuredData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Obada\Entities\RequestObitDID;
use App\Http\Handlers\Handler;

class Save extends Handler {
    public function __invoke(Request $request) {
        $input = $request->input();

        try {
            $requestDID = (new RequestObitDID)
                ->setManufacturer($request->get('manufacturer'))
                ->setPartNumber($request->get('part_number'))
                ->setSerialNumber($request->get('serial_number'));

            $did = ObadaClient::generateDID($requestDID);
        } catch (\Exception $e) {
            return response()->json([
                'errorMessage'=>$e->getMessage()
            ], 400);
        }

        if($request->get('device_id') != 0) {
            $device = Device::with('metadata','documents','structured_data')->find($request->get('device_id'));
            if(!$device) {
                return response()->json([
                    'status' => 1,
                    'errorMessage'=>'Unable to find device'
                ], 400);
            }
        } else {
            Log::info("USN2", ['usn' => $did]);

            $existingDevice = Device::where(['obit_did' => $did->getDid()])->first();

            if($existingDevice) {
                return response()->json([
                    'errorMessage'=>'Device With This USN Already Exists'
                ], 400);
            }

            $device = new Device();
        }

        $device->manufacturer = $input['manufacturer'];
        $device->part_number = $input['part_number'];
        $device->serial_number = $input['serial_number'];
        $device->owner = $input['owner'];
        $device->status = $input['status'];

        $device->usn = '';
        $device->obit_did = $did->getDid();
        $device->save();
        if(isset($input['metadata']) && $input['metadata']) {
            foreach($input['metadata'] as $m) {
                if(isset($m['id'])) {
                    $metadata = Metadata::find($m['id']);
                    if(!$metadata) {
                        $metadata = new Metadata();
                    }
                } else {
                    $metadata = new Metadata();
                }
                $metadata->device_id = $device->id;
                $metadata->metadata_type_id = $m['metadata_type_id'];
                if(isset($m['data_fp']))
                    $metadata->data_fp = $m['data_fp'];

                if(isset($m['data_txt']))
                    $metadata->data_txt = $m['data_txt'];

                if(isset($m['data_int']))
                    $metadata->data_int = $m['data_int'];
                $metadata->data_hash = '';
                $metadata->save();
            }
        }

        if(isset($input['structured_data']) && $input['structured_data']) {
            foreach($input['structured_data'] as $s) {
                if(isset($s['id'])) {
                    $structured_data = StructuredData::find($s['id']);
                    if(!$structured_data) {
                        $structured_data = new StructuredData();
                    }
                } else {
                    $structured_data = new StructuredData();
                }
                $structured_data->device_id = $device->id;
                $structured_data->structured_data_type_id = $s['structured_data_type_id'];
                $structured_data->data_array = $s['data_array'];
                $structured_data->data_hash = $structured_data->getHash();
                $structured_data->save();
            }
        }

        if(isset($input['documents']) && $input['documents']) {
            foreach($input['documents'] as $d) {
                if(isset($d['id'])) {
                    $document = Documents::find($d['id']);
                    if(!$document) {
                        $document = new Documents();
                    }
                } else {
                    $document = new Documents();
                }
                $document->device_id = $device->id;
                $document->doc_type_id = $d['doc_type_id'];
                $document->doc_path = $d['doc_path'];
                $document->data_hash = $document->getHash();
                $document->save();
            }
        }

        if(isset($input['structured_data_to_remove']) && $input['structured_data_to_remove']) {
            foreach($input['structured_data_to_remove'] as $s) {
                $structured_data = StructuredData::find($s);
                if($structured_data){
                    $structured_data->delete();
                }
            }
        }

        if(isset($input['metadata_to_remove']) && $input['metadata_to_remove']) {
            foreach($input['metadata_to_remove'] as $m) {
                $metadata = Metadata::find($m);
                if($metadata){
                    $metadata->delete();
                }
            }
        }

        if(isset($input['documents_to_remove']) && $input['documents_to_remove']) {
            foreach($input['documents_to_remove'] as $d) {
                $document = Documents::find($d);
                if($document){
                    $document->delete();
                }
            }
        }

        try {
            Log::info('payload', ['payload' => $device->getLocalObit()]);

            $result = ObadaClient::checksum($device->getLocalObit());

            Log::info('root hash', ['rh' => $result['rootHash']]);

            return response()->json([
                'status' => 0,
                'root_hash'=> $result['checksum'],
                'device'   => $device
            ], 200);

        } catch(\Exception $e) {
            Log::info($e->getMessage());
            return response()->json([
                'status' => 1,
                'errorMessage'=>'Error Generating Device Root Hash'
            ], 400);
        }
    }
}
