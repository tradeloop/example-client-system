@extends('layouts.app',['body_class'=>'landing-page'])

@section('head')
    <title>__title__</title>
    <meta name="description" content="__description__">
    <meta name="keywords" content="__keywords__">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css"/>

    <script>
        var _device_id = '<?php echo $device_id; ?>';
        var _schema = <?php echo $schema?$schema->toJson():'{}'; ?>
    </script>

@endsection

@section('content')
    @include('common.nav',['fixed'=>false])
    <div class="main">
        <div class="container">
            <h1 class="text-center">
                <?php if($device_id == 0) { ?>
                    Add Device

                <?php } else { ?>
                    Edit Device

                <?php } ?>
            </h1>
            <section class="py-5 my-5">
                <edit-device :device_id="_device_id" :schema="_schema"></edit-device>
            </section>
        </div>

    </div>
    @include('common.footer')
@endsection

@section('scripts')
@endsection
