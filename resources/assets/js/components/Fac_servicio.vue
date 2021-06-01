<template>
    <main class="main">
    <!-- Breadcrumb -->
    <div class="container-fluid">
        <!-- Ejemplo de tabla Listado -->
        <div class="card">
        <div class="card-header">
                <i class="fa fa-align-justify"></i> Factura servicio
            <button type="button" @click="abrirModal('fac_servicios','registrar')" class="btn btn-success btn-sm" >
                <i class="icon-plus"></i>&nbsp;Nuevo
            </button>
        </div>
        <div class="card-body">
            <div class="form-group row">
                <div class="col-md-6">
                    <div class="input-group">
                        <select class="form-control col-md-3" v-model="criterio">
                            <option value="nombre">Nombre</option>
                        </select>
                    <input type="text" v-model="buscar" @keyup.enter="listarFac_servicio(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                    <button type="submit" @click="listarFac_servicio(1,buscar,criterio)" class="btn btn-primary">
                        <i class="fa fa-search"></i> Buscar
                    </button>
                    </div>
                </div>
            </div>
            <table class="table table-bordered table-striped table-sm">
                <thead>
                    <tr class="p-3 mb-2 bg-dark text-white">
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Opciones</th>
                    </tr>
                </thead>
            <tbody>
                <tr v-for="objeto in arrayFac_servicio" :key="objeto.id">
                <td v-text="objeto.nombre"></td>
                <td v-text="objeto.desc"></td>
                <td>
                    <md-button class="md-icon-button " @click="abrirModal('fac_servicios','actualizar',objeto)" title="Actualizar">                         
                    <i class="material-icons Color3">edit</i>
                    </md-button>
                    <md-button class="md-icon-button md-primary " @click="eliminarFac_servicio(objeto)" title="Eliminar">
                    <i class="material-icons Color4">delete</i>
                    </md-button>  
                    <!-- <md-button class="md-icon-button " @click="mostrarActualizar(objeto)" title="Actualizar">                         
					<i class="material-icons Color3">edit</i>
					</md-button>                 -->
                </td>
                </tr>
            </tbody>
            </table>
                <nav>
                    <ul class="pagination">
                        <li class="page-item" v-if="pagination.current_page > 1">
                            <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1, buscar, criterio)">Ant</a>
                        </li>
                        <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                            <a class="page-link" href="#" @click.prevent="cambiarPagina(page, buscar, criterio)" v-text="page"></a>
                        </li>
                        <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                            <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1, buscar, criterio)">Sig</a>
                        </li>
                    </ul>
                </nav> 
            </div>
        </div>
        <!-- Fin ejemplo de tabla Listado -->
    </div>
    <!--Inicio del modal agregar/actualizar-->
    <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <h4 class="modal-title" v-text="tituloModal"></h4>
                    <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                        <span aria-hidden="true">X</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action method="post" enctype="multipart/form-data" class="form-horizontal">
                        <md-card-content>
                            <div class="md-layout-item md-size-50">
								<md-field :class="getValidationClass('nombre')">
									<label for="nombre">Nombre</label>
									<md-input type="text" id="nombre" name="nombre" autocomplete="given-name" v-model="form.nombre" :disabled="sending" />
									<span class="md-error" v-if="!$v.form.nombre.required">El nombre es requerido</span>																																	               
								</md-field>
							</div>&nbsp;&nbsp;&nbsp;
                            <div class="md-layout-item md-size-100">
								<md-field :class="getValidationClass('desc')">
									<label for="desc">Descripción</label>
									<md-input type="text" id="desc" name="desc" autocomplete="given-name" v-model="form.desc" :disabled="sending" />
									<span class="md-error" v-if="!$v.form.desc.required">La descripción es requerida</span>																																	               
                                </md-field>
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
                        <md-button type="submit" v-if="tipoAccion==2" class="md-dense md-raised md-primary" :disabled="sending" @click="actualizarFac_servicio()">Actualizar</md-button>
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
import { required, minLength } from "vuelidate/lib/validators";

export default {
    mixins: [validationMixin],
    data() {
        return {

        form: {
            nombre: " ",
            desc: " ",
        },
        sending: false,
        arrayFac_servicio: [],
        id_servicio: 0,

        modal: 0,
        tituloModal: "",
        tipoAccion: 0,
        pagination: {
            total: 0,
            current_page: 0,
            per_page: 0,
            last_page: 0,
            from: 0,
            to: 0
        },
        offset: 3,
        criterio: "nombre",
        buscar: ""
        };
    },

    validations: {
        form: {
        nombre: {
            required
        },
        desc: {
            required
        },
        }
    },

     computed: {
    isActived: function() {
      return this.pagination.current_page;
    },
    //Calcula los elementos de la paginación
    pagesNumber: function() {
      if (!this.pagination.to) {
        return [];
      }

      var from = this.pagination.current_page - this.offset;
      if (from < 1) {
        from = 1;
      }

      var to = from + this.offset * 2;
      if (to >= this.pagination.last_page) {
        to = this.pagination.last_page;
      }

      var pagesArray = [];
      while (from <= to) {
        pagesArray.push(from);
        from++;
      }
      return pagesArray;
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
            this.registrarFac_servicio();
            this.clearForm();
        }
        },
        clearForm() {
        this.$v.$reset();
        this.form.nombre = null;
        this.form.desc = null;
        
        },

        listarFac_servicio(page, buscar, criterio) {
                let me=this;
                var url= '/fac_servicio?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayFac_servicio = respuesta.fac_servicios.data;
                    me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        
        cambiarPagina(page, buscar, criterio) {
        let me = this;
        //Actualiza la p�gina actual
        me.pagination.current_page = page;
        //Envia la petici�n para visualizar la data de esa p�gina
        me.listarFac_servicio(page, buscar, criterio);
        },

        registrarFac_servicio() {
            let me = this;
                axios.post('/fac_servicio/registrar',{
                    'nombre': this.form.nombre.toUpperCase(),
                    'desc': this.form.desc.toUpperCase(),
                }).then(function (response) {
                    me.listarFac_servicio(1,'','nombre');
                    me.mensaje("Guardado", "Guardo");
                    me.cerrarModal();
                }).catch(function (error) {
                    console.log(error);
                });
            },
        actualizarFac_servicio() {
        let me = this;

        axios
            .put("/fac_servicio/actualizar", {
            id: this.id_servicio,
            nombre: this.form.nombre.toUpperCase(),
            desc: this.form.desc.toUpperCase(),
            })
            .then(function(response) {
                me.listarFac_servicio(1, "", "nombre");
                me.mensaje("Actualizado", "Actualizó ");
                me.cerrarModal();
            })
            .catch(function(error) {
            console.log(error);
            });
        },
        mostrarActualizar(data = []) {
			let me = this;
			(this.tipoAccion = 2), (me.listado = 0);
			(this.id_servicio = data["id"]);
			this.form.nombre = data["nombre"];
			this.form.desc = data["desc"];
		},
        eliminarFac_servicio(data = []) {
        swal({
            title: "Esta seguro de Eliminar esta factura de servicio " + data["nombre"],
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Aceptar!",
            cancelButtonText: "Cancelar",
            confirmButtonClass: "btn btn-success",
            cancelButtonClass: "btn btn-danger",
            buttonsStyling: false,
            reverseButtons: true
        }).then(result => {
            if (result.value) {
            let me = this;
            this.id_servicio = data["id"];
            axios
                .post("/fac_servicio/eliminar", {
                id: this.id_servicio
                })
                .then(function(response) {
                me.cerrarModal();
                me.listarFac_servicio(1, "", "nombre");
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
            case "fac_servicios": {
            switch (accion) {
                case "registrar": {
                this.modal = 1;
                this.form.nombre="";
                this.tituloModal = "Registrar factura de servicio";
                this.tipoAccion = 1;
                break;
                }
                // muestra la información en el componente vue
                case "actualizar": {
                //console.log(data);
                this.modal = 1;
                this.tituloModal = "Actualizar factura de servicio";
                this.tipoAccion = 2;
                this.id_servicio = data["id"];
                this.form.nombre = data["nombre"];
                this.form.desc = data["desc"];
                
                // nombre v-model        datos que vienen del controlador (Main)
                break;
                }
            }
            }
        }
        },
    },

    mounted() {
        this.listarFac_servicio(1, this.buscar, this.criterio);
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
</style>
