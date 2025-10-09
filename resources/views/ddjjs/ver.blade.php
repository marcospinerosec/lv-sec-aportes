<div class="box-body responsive-table">
    <div id="lista_item_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
        <div class="row">
            <div class="col-sm-12">
                <table id="tEmpleados" class="display" cellspacing="0" width="100%">
                    <thead>
                    <tr>

                        <th>CUIL</th>
                        <th>Nombre</th>
                        <th>Categoría</th>

                        <th>Afiliado</th>
                        <th>Ingreso</th>
                        <th>Novedad</th>
                        <th>Egreso</th>
                        <th>Rem.Art.100</th>
                        <th>Rem.Cuota Afil.</th>

                    </tr>
                    </thead>
                    <tbody>
                    @foreach($empleados as $empleado)
                        <tr>
                            <td>{{ $empleado->Cuil }}</td>
                            <td>{{ $empleado->Nombre }}</td>
                            <td>{{ $empleado->Categoria }}</td>
                            <td>{{ ($empleado->Afiliado) ? 'SI' : 'NO' }}</td>
                            <td data-order="{{ $empleado->FechaIngreso ? date('Y-m-d', strtotime($empleado->FechaIngreso)) : '' }}">
                                {{ $empleado->FechaIngreso ? date('d/m/Y', strtotime($empleado->FechaIngreso)) : '' }}
                            </td>
                            <td>{{ $empleado->Novedad }}</td>
                            <td data-order="{{ $empleado->FechaEgreso ? date('Y-m-d', strtotime($empleado->FechaEgreso)) : '' }}">
                                {{ $empleado->FechaEgreso ? date('d/m/Y', strtotime($empleado->FechaEgreso)) : '' }}
                            </td>
                            <td>{{ number_format($empleado->ImporteArt100, 2, ',', '.') }}</td>
                            <td>{{ number_format($empleado->ImporteCuotaAfil, 2, ',', '.') }}</td>
                        </tr>
                    @endforeach
                    </tbody>

                    {{-- Totales --}}
                    <tfoot>
                    @if(!empty($ddjjTotales))
                        @php $tot = $ddjjTotales[0]; @endphp
                        <tr style="font-weight:bold; background-color:#f0f0f0;">
                            <td colspan="7" class="text-right">Totales:</td>
                            <td>{{ number_format($tot->ImporteArt100, 2, ',', '.') }}</td>
                            <td>{{ number_format($tot->ImporteCuotaAfil, 2, ',', '.') }}</td>
                        </tr>
                    @endif
                    </tfoot>
                </table>



            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        var spanishTranslation = {
            "sProcessing":     "Procesando...",
            "sLengthMenu":     "Mostrar _MENU_ registros",
            "sZeroRecords":    "No se encontraron resultados",
            "sEmptyTable":     "Ningún dato disponible en esta tabla",
            "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix":    "",
            "sSearch":         "Buscar:",
            "sUrl":            "",
            "sInfoThousands":  ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst":    "Primero",
                "sLast":     "Último",
                "sNext":     "Siguiente",
                "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }
        };
        var table = $('#tEmpleados').DataTable({
            "language": spanishTranslation
        });
        $('[data-toggle="tooltip"]').tooltip();

        table.on('draw', function() {
            $('[data-toggle="tooltip"]').tooltip();
        });
    });
    function confirmDel(url){
//var agree = confirm("¿Realmente desea eliminarlo?");
        if (confirm("¿Realmente deseas eliminar estos datos?"))
            window.location.href = url;
        else
            return false ;
    }




</script>
