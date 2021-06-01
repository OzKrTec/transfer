@extends('principal')
@section('contenido')

    @if(Auth::check())
            @if (Auth::user()->idrol == 1)
            <template v-if="menu==0">
                <h1>Escritorio</h1>
            </template>

            <template v-if="menu==4">
                <accesorio></accesorio>
            </template>

            <template v-if="menu==64">
                <user></user>
            </template>

            <template v-if="menu==8">
                <rol></rol>
            </template>

            <template v-if="menu==89999">
                <venta></venta>
            </template>

            <template v-if="menu==89922">
                <libro></libro>
            </template>

            <template v-if="menu==89921">
                <sector :ruta="ruta"></sector>
            </template>

            <template v-if="menu==89924">
                <barrio :ruta="ruta"></barrio>
            </template>
            <template v-if="menu==24">
                <fac_det_servicio></fac_det_servicio>
            </template>
            <template v-if="menu==112">
                <fac_servicio :ruta="ruta"></fac_servicio>
            </template>

            @elseif (Auth::user()->idrol == 2)
            <template v-if="menu==5">
                <venta></venta>
            </template>

            <template v-if="menu==6">
                <cliente></cliente>
            </template>
            <template v-if="menu==10">
                <h1>Reporte de ventas</h1>
            </template>

            <template v-if="menu==11">
                <h1>Ayuda</h1>
            </template>

            <template v-if="menu==12">
                <h1>Acerca de</h1>
            </template>

            @elseif (Auth::user()->idrol == 3)

            <template v-if="menu==76">
                <red></red>
            </template>
   
            @else

            @endif

    @endif
       
        
    @endsection