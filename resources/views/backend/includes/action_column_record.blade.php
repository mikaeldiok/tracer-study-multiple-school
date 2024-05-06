<div class="text-right">
    <x-buttons.edit class="m-1" route='{!!route("backend.$module_name.edit", $data)!!}' title="{{__('Edit')}} {{ ucwords(Str::singular($module_name)) }}" small="true" />
</div>
