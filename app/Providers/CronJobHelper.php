<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CronJobHelper
{
    public $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function getManualMarketing_data(int $id = 0, string $type = "wapp"): array
    {
        $p_data = DB::table("marketing_manual_index")->select([
            "marketing_manual_index.*",
        ])->where([
            "marketing_manual_index.id" => $id,
            "marketing_manual_index.status" => "0",
            "marketing_manual_index.deleted" => "0",
        ]);

        if ("wapp" == strtolower($type)) {
            $p_data = $p_data->where("marketing_manual_index.is_wapp", "1");
        } else if ("text" == strtolower($type)) {
            $p_data = $p_data->where("marketing_manual_index.is_phone", "1");
        } else {
            $p_data = $p_data->where("marketing_manual_index.status", "x");
        }

        if ([] == collect($p_data->first())->toArray()) {
            return [];
        }

        if ("wapp" == strtolower($type)) {
            return collect(DB::table("marketing_manual_data")->select([
                "marketing_manual_data.id as p_id",
                "marketing_manual_data.phone",
            ])->where([
                "marketing_manual_data.marketing_manual_index_id" => $id,
            ])->groupBy("marketing_manual_data.phone")->pluck("marketing_manual_data.phone"))->toArray();
        }

        return collect(DB::table("marketing_manual_data")->select([
            "marketing_manual_data.id as p_id",
            "marketing_manual_data.phone",
        ])->where([
            "marketing_manual_data.marketing_manual_index_id" => $id,
            "marketing_manual_data.is_dnd" => "0",
        ])->groupBy("marketing_manual_data.phone")->pluck("marketing_manual_data.phone"))->toArray();
    }

    public function manualMarketing_whatsapp($id, $type)
    {
        $arr_data = $this->getManualMarketing_data($id, $type);
        if ([] == $arr_data) {
            return [];
        }

        $res_func = $this->aisensy_whatsapp($arr_data);

        return $res_func;
    }

    public function aisensy_whatsapp($data)
    {


        return $data;
    }
}