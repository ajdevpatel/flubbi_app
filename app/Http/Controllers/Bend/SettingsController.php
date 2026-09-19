<?php

namespace App\Http\Controllers\Bend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class SettingsController extends Controller
{

    public function webOptionPostIndex(Request $request)
    {
        if ("POST" === $request->method()) {
            Validator::make($request->all(), [
                "keys" => "required",
                $request->keys => "required",
            ])->validate();

            $key = $request->keys;
            DB::table("web_options")->where("op_key", $key)->update([
                "op_value" => $request->$key,
                "updated_by" => $this->getLoginUserId(),
                "updated_at" => $this->currentDataTime(),
            ]);
            return redirect()->back()->with('success', 'The data has been successfully updated');
        }

        $option_list = collect(DB::table("web_options")->where("web_options.deleted", "0")->get())->toArray();
        return view("backend.webOptionPostIndex", [
            "option_list" => $option_list,
        ]);
    }

    public function messageConfigurationPostIndex(Request $request)
    {
        $message_configuration = DB::table("message_configuration")->select([
            "message_configuration.*",
        ])->get();

        return view("backend.messageConfigurationIndex", [
            "message_list" => $message_configuration
        ]);
    }


    public function messageConfigurationEdit(string $id = null, Request $request)
    {
        Validator::make($request->all(), [
            "uuid" => "required|exists:message_configuration,uuid",
        ])->validate();

        $table_data = DB::table("message_configuration")->select([
            "message_configuration.*",
        ])->where("message_configuration.uuid", $id)->first();

        if ([] == collect($table_data)->toArray()) {
            return redirect()->back()->withErrors("It does not exist a message format system.");
        }

        if ("POST" === $request->method()) {
            Validator::make($request->all(), [
                "op_value" => "required",
            ])->validate();

            DB::table("message_configuration")->where("message_configuration.uuid", $id)->update([
                "op_value" => $request->op_value,
                "updated_by" => $this->getLoginUserId(),
                "updated_at" => $this->currentDataTime(),
            ]);
            return redirect()->route("_messageConfigurationPostIndex")->with('success', 'The data has been successfully updated');
        }

        return view("backend.messageConfigurationEdit", [
            "data" => $table_data
        ]);
    }
}
