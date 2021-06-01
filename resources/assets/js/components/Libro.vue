<template>
    <main class="main">
        <!-- Breadcrumb -->
        <div class="container-fluid">
            <!-- Ejemplo de tabla Listado -->
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Libros 
                    <button type="button" @click="abrirModal('libro','registrar')" class="btn btn-success btn-sm" >
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered display" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>                    
                                    <th>Nombre</th>
                                    <th>Código</th>
                                    <th>Número páginas</th>
                                    <th>Edición</th>
                                    <!-- <th>Estado</th> -->
                                    <th>Opciones</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                            <tfoot>
                                <tr>                    
                                    <th>Nombre</th>
                                    <th>Código</th>
                                    <th>Número páginas</th>
                                    <th>Edición</th>
                                    <!-- <th>Estado</th> -->
                                    <th>Opciones</th>
                                </tr>
                            </tfoot>
                        </table>
					</div>
                </div>
            </div>
            <!-- Fin ejemplo de tabla Listado -->
        </div>
        <!--Inicio del modal agregar/actualizar-->
        <div class="modal fade" id="modalNuevo" :class="{'mostrar': modal}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" v-text="tituloModal"></h4>
                        <button type="button" class="close" @click="cerrarModal()" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">X</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action method="post" enctype="multipart/form-data" class="form-horizontal">
                            <md-card-content>
                                <div class="md-layout">
                                    <div class="md-layout-item md-size-40">
                                        <md-field :class="getValidationClass('nombre')">
                                            <label for="nombre">Nombre</label>
                                            <md-input type="text" id="nombre" name="nombre" autocomplete="given-name" v-model="form.nombre" :disabled="sending" />
                                            <span class="md-error" v-if="!$v.form.nombre.required">El nombre es requerido</span>																																	               
                                        </md-field>
                                    </div>&nbsp;&nbsp;&nbsp;
                                    <div class="md-layout-item md-size-35">
                                        <md-field :class="getValidationClass('codigo')">
                                            <label for="codigo">Código</label>
                                            <md-input type="number" id="codigo" name="codigo" autocomplete="given-name" v-model="form.codigo" :disabled="sending" />
                                            <span class="md-error" v-if="!$v.form.codigo.required">El código es requerido</span>																																	               
                                        </md-field>
                                    </div>
                                </div>
                                <div class="md-layout">
                                    <div class="md-layout-item md-size-40">
                                        <md-field :class="getValidationClass('n_pag')">
                                            <label for="n_pag">Número páginas</label>
                                            <md-input type="number" id="n_pag" name="n_pag" autocomplete="given-name" v-model="form.n_pag" :disabled="sending" />
                                            <span class="md-error" v-if="!$v.form.n_pag.required">El número de páginas es requerido</span>																																	               
                                            <span class="md-error" v-else-if="!$v.form.n_pag.maxlength">El número de páginas es máximo 7 digitos</span>
                                        </md-field>
                                    </div>&nbsp;&nbsp;&nbsp;
                                    <div class="md-layout-item md-size-35">
                                        <md-field>
                                            <label for="edicion">Edición</label>
                                            <md-input type="number" id="edicion" name="edicion" autocomplete="given-name" v-model="edicion" :disabled="sending" />
										</md-field>
                                    </div>
                                </div>
                            </md-card-content>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <md-card-actions>
                            <md-button type="button" class="md-raised" @click="cerrarModal()">Cerrar</md-button>
                        </md-card-actions>
                        <md-card-actions>
                            <md-button type="submit" v-if="tipoAccion==1" class="md-dense md-raised md-primary" :disabled="sending" @click="validarDatos()">Guardar</md-button>
                        </md-card-actions>
                        <md-card-actions>
                            <md-button type="submit" v-if="tipoAccion==2" class="md-dense md-raised md-primary" :disabled="sending" @click="actualizarLibro()">Actualizar</md-button>
                        </md-card-actions>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin del modal-->
    </main>
</template>

<script>
    import { validationMixin } from "vuelidate";
    import { MdButton, MdContent, MdField,MdAutocomplete,MdCard, MdMenu, MdList } from "vue-material/dist/components";
    // import VueMaterial from 'vue-material'
    // Vue.use(VueMaterial)
    Vue.use(MdButton);
    Vue.use(MdContent);
    Vue.use(MdField);
    Vue.use(MdAutocomplete);
    Vue.use(MdCard);
    Vue.use(MdMenu);
    Vue.use(MdList);
    import { required, minLength, maxLength } from "vuelidate/lib/validators";

    export default {
        mixins: [validationMixin],

        data() {
            return {

            edicion: "",
            form: {
                nombre: "",
                codigo: "",
                n_pag: ""
            },
            sending: false,
            id_libro: 0,
            arrayLibro: [],

            modal: 0,
            tituloModal: "",
            tipoAccion: 0,
            };
        },

        validations: {
            form: {
                nombre: {
                    required
                },
                codigo: {
                    required
                },
                n_pag: {
                    required,
                    maxLength: maxLength(7)
                },
            }
        },

        methods: {
            getValidationClass(fieldName) {
                const field = this.$v.form[fieldName];
                if (field) {
                    return {
                    "md-invalid": field.$invalid && field.$dirty
                    };
                }
            },
            nameWithLang({ nombre }) {
                return `${nombre}`;
            },
            validarDatos() {
                this.$v.$touch();

                if (!this.$v.$invalid) {
                    this.registrarLibro();
                    this.clearForm();
                }
            },
            clearForm() {
                this.$v.$reset();
                this.form.nombre = null;
                this.form.codigo = null;
                this.form.n_pag = null;
                this.edicion = null;
            },

            listarLibro() {
                let me=this;
                var url= '/libro';
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayLibro = respuesta.libros.data;
                    me.myTable(me.arrayLibro);
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            myTable(datas){
		    let me = this;

                $(document).ready(function() {
                
                    var table = $('#dataTable').DataTable({
                        dom: 'Blfrtip',
                        buttons: [{
                            //Botón para Excel
                            extend: 'csv',
                            footer: true,
                            title: 'Archivo',
                            filename: 'Export_File',

                            //Aquí es donde generas el botón personalizado
                            text: '<button class="btn btn-success btn-sm">CSV <i class="fas fa-file-excel"></i></button>'
                        },
                        //Botón para PDF
                        {
                            extend: 'pdf',
                            footer: true,
                            title: 'Archivo PDF',
                            filename: 'Export_File_pdf',
                            text: '<button class="btn btn-danger btn-sm">PDF <i class="far fa-file-pdf"></i></button>'
                        },
                        // botón para world
                        {
                            extend: 'excel',
                            footer: true,
                            title: 'Archivo excel',
                            filename: 'Export_File_Excel',
                            text: '<button class="btn btn-info btn-sm">EXCEL <i class="fas fa-file-excel"></i></button>'
                        }],

                        destroy: true,
                        stateSave: true,
                        data:datas,
                        "createdRow": function( row, data, dataIndex){
                            if( data[6] ==  `0`){
                                $(row).addClass('redClass');
                            }
                        },
                        "language": {
                            "lengthMenu": "Ver _MENU_ registros por página",
                            "zeroRecords": "NO existen Datos",
                            "info": "mostrando la página _PAGE_ de _PAGES_",
                            "infoEmpty": "No hay registros disponibles",
                            "search":         "Buscar:",
                            "paginate": {
                                "first":      "Prim",
                                "last":       "Ant",
                                "next":       "Sig",
                                "previous":   "Ant"
                            },
                            "infoFiltered": "(filtrado de _MAX_ total registros)"
                        },
                        responsive: "true",
                        "columns": [
                            { "data": "nombre" },
                            { "data": "codigo" },
                            { "data": "n_pag" },
                            { "data": "edicion" },
                            // {"defaultContent": "<button class='btn'> <i class='fa fa-home'></i></button>"}
                            // {"defaultContent": "<button type='button' @click='hola() class='btn btn-success btn-sm' data-tooltip title='Actualizar' > <i class='icon-pencil'></i>  </button> "},
                            {"defaultContent": "<button type='button' id='editar' class='editar btn btn-success btn-sm' data-tooltip title='Editar' > <i class='fas fa-edit'></i> </button> <button type='button' id='eliminar' class='eliminar btn btn-danger btn-sm' data-tooltip title='Eliminar' > <i class='icon-trash'></i>  </button>"},
                            // {"defaultContent": "}
                            // {"defaultContent": " <button type='button' class='md-raised' @click='this.hola()'> Cerrar </button>"}
                        ]
                    });

                    $('#dataTable tbody').on( 'click', '.editar', function () {
                        me.datos = table.row( $(this).parents('tr') ).data();
                        me.abrirModal('libro','actualizar',me.datos);//libro y actualizar de la modal
                        //  console.log(data['nombre']);
                    });
                    $('#dataTable tbody').on( 'click', '.eliminar', function () {
                        me.datos= table.row( $(this).parents('tr') ).data();
                        me.eliminarLibro(me.datos);
                        //  console.log(data['modelo']);
                    });
                    
                });
		    },
            registrarLibro() {
                let me = this;
                    axios.post('/libro/registrar',{
                        'nombre': this.form.nombre,
                        'codigo': this.form.codigo,
                        'n_pag': this.form.n_pag,
                        'edicion': this.edicion,
                    }).then(function (response) {
                        me.cerrarModal();
                        me.listarLibro();
                        me.mensaje("Guardado", "Guardo");
                    }).catch(function (error) {
                        console.log(error);
                    });
            },
            actualizarLibro() {
            let me = this;

            axios
                .put("/libro/actualizar", {
                nombre: this.form.nombre,
                codigo: this.form.codigo,
                n_pag: this.form.n_pag,
                edicion: this.edicion,
                id: this.id_libro
                })
                .then(function(response) {
                me.cerrarModal();
                me.listarLibro();
                me.mensaje("Actualizado", "Actualizó ");
                })
                .catch(function(error) {
                console.log(error);
                });
            },
            eliminarLibro(data = []) {
                swal({
                    title: "¿Esta seguro de Eliminar el libro " + data["nombre"],
                    type: "warning",
                    title: "¿Estás seguro?",
                    text: "¡No podrás revertir esto!",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    cancelButtonText: "¡No, cancélalo!",
                    confirmButtonText: "¡Si, bórralo!"
                }).then(result => {
                    if (result.value) {
                    let me = this;
                    this.id_libro = data["id"];
                    axios
                        .post("/libro/eliminar", {
                        id: this.id_libro
                        })
                        .then(function(response) {
                            me.cerrarModal();
                            me.listarLibro();
                            me.mensaje("Eliminado", "Eliminó ");
                        })
                        .catch(function(error) {
                        console.log(error);
                        });
                    } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                    ) {
                    }
                });
            },

            mensaje(tipo, crud) {
            swal(tipo, "El registro se " + crud + " con éxito.", "success");
            },

            cerrarModal() {
            this.modal = 0;
            this.tituloModal = "";
            this.clearForm();
            },
            abrirModal(modelo, accion, data = []) {
                switch (modelo) {
                    case "libro": {// libro de el boton nuevo al inicio
                        switch (accion) {
                            case "registrar": {
                            this.modal = 1;
                            this.form.nombre="";
                            this.form.codigo="";
                            this.form.n_pag="";
                            this.edicion="";
                            this.tituloModal = "Registrar libro";
                            this.tipoAccion = 1;
                            break;
                            }
                            // muestra la información en el componente vue
                            case "actualizar": {
                            //console.log(data);
                            this.modal = 1;
                            this.tituloModal = "Actualizar libro";
                            this.tipoAccion = 2;
                            this.id_libro = data["id"];
                            this.form.nombre = data["nombre"];
                            this.form.codigo = data["codigo"];
                            this.form.n_pag = data["n_pag"];
                            this.edicion = data["edicion"];
                            
                            // nombre v-model        datos que vienen del controlador (Main)
                            break;
                            }
                        }
                    }
                }
            },
        },

        mounted() {
            this.listarLibro();
        }
    };
</script>
<style>
	.modal-content {
	width: 100% !important;
	position: absolute !important;
	}
	.mostrar {
	display: list-item !important;
	opacity: 1 !important;
	position: absolute !important;
	background-color: #3c29297a !important;
	}
	.div-error {
	display: flex;
	justify-content: center;
	}
	.text-error {
	color: red !important;
	font-weight: bold;
	}
	.material-icons.Color1 { color: rgb(31, 33, 34); }
	.material-icons.Color2 { color: rgba(223, 195, 37, 0.966); }
	.material-icons.Color3 { color: rgb(12, 170, 91); }
	.material-icons.Color4 { color: rgba(228, 54, 54, 0.863); }

    .dt-button {
    padding: 0;
    border: none;
    }
</style>