<?php
?>


@foreach($colorSettings as $colorSetting)
    <div class="color-setting">
        <input type="hidden" class="warning-id" value="{{$colorSetting->id}}">
        <span class="title">{{$colorSetting->name}}</span>
        <?php $colorSettingLevel = $colorSetting->level;
        ?>
        <div>
            <span id="color-span-{{$colorSettingLevel}}" class="color-square"></span>
            <input class="input-color" type="text" class="form-control color-input" id="color-input-{{$colorSettingLevel}}">
            <button type="button"
                    class="btn btn-default jscolor {valueElement:'color-input-{{$colorSettingLevel}}',styleElement:'color-span-{{$colorSettingLevel}}',value:'{{$colorSetting->color}}'}">
                Chọn màu
            </button>

            <span class="text-from">Từ</span>
            <input class="input-from" type="text" class="form-control text-range" value="{{$colorSetting->from_number}}">

            <span>mm</span>

            <span class="text-to">Đến</span>
            <input class="input-to" type="text" class="form-control text-range" value="{{$colorSetting->to_number}}">

            <span>mm</span>

        </div>
    </div>
    <hr/>
@endforeach

<div>
    <button type="button" class="btn btn-primary pull-right" id="btnColorSetting">Lưu</button>
</div>


<script>

</script>
