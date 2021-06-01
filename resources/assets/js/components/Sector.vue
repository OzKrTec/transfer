<template>
    <main class="main">
    <!-- Breadcrumb -->
        <div class="container-fluid">
        <!-- Ejemplo de tabla Listado -->
			<div class="card">
				<div class="card-header">
                    <i class="fa fa-align-justify"></i> Sectores
                    <button type="button" @click="abrirModal('sectores','registrar')" class="btn btn-success btn-sm" >
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                </div>
				<div class="card-body">
					<div class="md-layout">
						<div class="md-layout-item">
							<md-field md-clearable>
								<label>Qué desea Buscar</label>
								<md-input v-model="buscar" @keypress="listarSector(1,buscar,criterio)"></md-input>
							</md-field>
						</div>&nbsp;&nbsp;&nbsp;
						<div class="md-layout-item">
							<br/> <md-button class="md-icon-button md-dense md-raised md-primary" @click="listarSector(1,buscar,criterio)">
							<md-icon>search</md-icon>
							</md-button>
						</div>
					</div>
					<div class="table-responsive">
						<table class="table table-bordered table-striped table-sm">
							<thead>
								<tr class="p-3 mb-2 bg-dark text-white">                            
									<th>Nombre</th>
									<th>Oficina</th>
									<th>Estado</th>
									<th>Opciones</th>
								</tr>
							</thead>
							<tbody>
								<tr  v-for="objeto in arraySector" :key="objeto.id" >                            
									<td v-text="objeto.nombre"></td>
									<td v-text="objeto.nomOficina"></td>
									<td> 
										<div v-if="objeto.edo">
											<span class="badge badge-success">Activo</span>
										</div>
										<div v-else>
											<span class="badge badge-danger">Desactivado</span>
										</div>
									</td>								
									<td>
										<md-button class="md-icon-button " @click="abrirModal('sectores','actualizar',objeto)" title="Actualizar">                         
											<i class="material-icons Color3">edit</i>
										</md-button>	
										<!-- <md-button class="md-icon-button md-primary " @click="eliminarSector(objeto)" title="Eliminar">
											<i class="material-icons Color4">delete</i>
										</md-button> -->
										<template v-if="objeto.edo">
											<md-button type="button" class="md-icon-button" @click="desactivarSector(objeto.id)">
												<i class="material-icons Color4">highlight_off</i>
											</md-button>
										</template>
										<template v-else>
											<md-button type="button" class="md-icon-button" @click="activarSector(objeto.id)">
												<i class="material-icons Color2">check_circle_outline</i>
											</md-button>
										</template>																			
									</td>									
								</tr>
							</tbody>
						</table>
					</div>
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
		</div>
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
                                <div class="md-layout">
                                    <div class="md-layout-item">
										<md-field :class="getValidationClass('nombre')">
											<label for="nombre">Nombre</label>
											<md-input type="text" id="nombre" name="nombre" autocomplete="given-name" v-model="form.nombre" :disabled="sending" />
											<span class="md-error" v-if="!$v.form.nombre.required">El nombre es requerido</span>																																	               
										</md-field>
									</div>
                                </div>
                            </md-card-content>
                        </form>
                    </div>
                    <div class="modal-footer">
						<md-card-actions>
							<md-button type="button" class="md-raised" @click="cerrarModal()">Cerrar</md-button>
							<md-button type="submit" v-if="tipoAccion==1" class="md-dense md-raised md-primary" :disabled="sending" @click="validarDatos()">Guardar</md-button>
							<md-button type="submit" v-if="tipoAccion==2" class="md-dense md-raised md-primary" :disabled="sending" @click="actualizarSector()">Actualizar</md-button>
						</md-card-actions>
					</div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
    </main>
</template>
<script>

    import { validationMixin } from "vuelidate";
    import Multiselect from "vue-multiselect";
    import Toasted from 'vue-toasted';
    import vSelect from "vue-select";
    import {
		MdButton,  
		MdContent,
		MdField,
		MdCard,
		MdMenu,
		MdSwitch,
		MdDatepicker,
		MdList
    } from "vue-material/dist/components";

    Vue.use(Toasted,  {
        iconPack : 'material' // set your iconPack, defaults to material. material|fontawesome|custom-class
    });
    Vue.use(MdButton);

    Vue.use(MdContent);
    Vue.use(MdField);
    Vue.use(MdCard);
    Vue.use(MdMenu);
    Vue.use(MdSwitch);
    Vue.use(MdList);
    Vue.use(MdDatepicker);
    import { required, minLength, maxLength } from "vuelidate/lib/validators";

export default {
	mixins: [validationMixin],
	props: ["ruta"],
	data() {
    
		return {
		form: {
			nombre:"",
		},
		id_oficina: "",
		sending: false,
		arrayOficina: [],
		arraySector:[],
		id_sector:0,

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
	components: {
		vSelect,
		Multiselect
	},
	validations: {
		form: {
			nombre: {
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
		},
		submittableDateTime() {
			const date = new Date(this.date);
			console.log(date);
			return date;
		},
		dateFormat() {
			return this.$material.locale.dateFormat ||"YYYY-MM-dd";
			// return moment(date).format('LL')
		},
		mdType() {
			switch (this.mdTypeValue) {
				case "fecCompra":
				return String;
			}
		}
	},
	methods: {
		//metodo que valida
		getValidationClass(fieldName) {
			const field = this.$v.form[fieldName];
			if (field) {
				return {
				"md-invalid": field.$invalid && field.$dirty
				};
			}
		},
		validarDatos() {
			this.$v.$touch();
			if (!this.$v.$invalid) {
				this.registrarSector();
				this.clearForm();
			}
		},
		clearForm() {
			this.$v.$reset();
			this.id_oficina = "";
			this.form.nombre="";
		},
		listarSector(page, buscar, criterio) {
            let me = this;
            var url ="/sector?page=" + page + "&buscar=" + buscar + "&criterio=" + criterio;
            axios.get(this.ruta + url).then(function (response) {
                    var respuesta = response.data;
                    me.arraySector = respuesta.sectores.data;
                    me.pagination = respuesta.pagination;
				}).catch(function (error) {
                    console.log(error);
            });
		},
		selectOficina() {
            let me = this;
            var url = "oficina/selectOficina";
            axios.get(this.ruta + url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayOficina = respuesta.oficinas;
                }).catch(function (error) {
                    console.log(error);
            });
        },
		cambiarPagina(page, buscar, criterio) {
			let me = this;
			me.pagination.current_page = page;
			me.listarSector(page, buscar, criterio);
		},
		cerrarModal() {
            this.modal = 0;
            this.tituloModal = "";
            this.clearForm();
        },
		registrarSector() {
		let me = this;
            axios.post(this.ruta + "/sector/registrar", {
                    'nombre': this.form.nombre.toUpperCase(),
					'id_oficina': this.id_oficina,
                }).then(function (response) {
					me.listarSector(1, "", "nombre");
                    me.mensaje("Guardado", "Guardo ");
                    me.cerrarModal();
                }).catch(function (error) {
                console.log(error);
                });
		},
		actualizarSector() {
		let me = this;
            axios.put(this.ruta + "/sector/actualizar", {
					'id':this.id_sector,
                    'nombre': this.form.nombre.toUpperCase(),
					'id_oficina': this.id_oficina,
            })
            .then(function (response) {
                me.listarSector(1, "", "nombre");
                me.mensaje("Actualizado", "Actualizo");
                me.cerrarModal();
            })
            .catch(function (error) {
                console.log(error);
            });
		},
		desactivarSector(id){
            swal({  
                title: '¿Está seguro de desactivar este producto?',
					type: 'warning',
					icon: 'warning',
					showCancelButton: true,
					confirmButtonText: 'Aceptar',
					cancelButtonText: 'Cancelar',
					// buttonsStyling: false,
					reverseButtons: true,
					}).then((result) => {
					if (result.value) {
						let me = this;
						axios.put(this.ruta + '/sector/desactivar',{
							'id': id
						}).then(function (response) {
							me.listarSector(1,'','nombre');
							me.mensaje("Desactivado", "Desactivo ");
						}).catch(function (error) {
							console.log(error);
						});    
					} else if (
						// Read more about handling dismissals
						result.dismiss === swal.DismissReason.cancel
					) {
					}
				}) 
        },
		activarSector(id){
			swal({
				title: '¿Está seguro de activar este producto?',
				type: 'warning',
				icon: 'warning',
				showCancelButton: true,
				confirmButtonText: 'Aceptar',
				cancelButtonText: 'Cancelar',
				// buttonsStyling: false,
				reverseButtons: true
				}).then((result) => {
				if (result.value) {
					let me = this;
					axios.put(this.ruta + '/sector/activar',{
						'id': id
					}).then(function (response) {
						me.listarSector(1,'','nombre');
						me.mensaje("Activado", "Activo ");
					}).catch(function (error) {
						console.log(error);
					});
				} else if (
					// Read more about handling dismissals
					result.dismiss === swal.DismissReason.cancel
				) {
				}
			}) 
		},
		abrirModal(modelo, accion, data = []) {
            switch (modelo) {
                case "sectores": {
                switch (accion) {
                    case "registrar": {
                    this.modal = 1;
                    this.form.nombre="";
                    this.id_oficina="";
                    this.tituloModal = "Registrar Sector";
                    this.tipoAccion = 1;
                    break;
                    }
                    // muestra la información en el componente vue
                    case "actualizar": {
                    //console.log(data);
                    this.modal = 1;
                    this.tituloModal = "Actualizar Sector";
                    this.tipoAccion = 2;
                    this.id_sector = data["id"];
                    this.form.nombre = data["nombre"];
                    this.id_oficina = data["id_oficina"];
                    
                    // nombre v-model        datos que vienen del controlador (Main)
                    break;
                    }
                }
                }
            }
        },
		mensaje(tipo, crud) {
			swal(tipo, "El registro se " + crud + " con éxito.", "success");
		}
	},
	mounted() {
		this.selectOficina();
		this.listarSector(1, this.buscar, this.criterio);
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