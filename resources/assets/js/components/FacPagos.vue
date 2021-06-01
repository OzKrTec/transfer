<template>
    <main class="main">
    <!-- Breadcrumb -->
        <div class="container-fluid">
        <!-- Ejemplo de tabla Listado -->
			<div class="card">
				<div class="card-header">
					<i class="fa fa-align-justify"></i>Pago Factura
					<button type="button" @click="mostraFormulario()" class="btn btn-success btn-sm">
						<i class="icon-plus"></i>&nbsp;Nuevo
					</button>
				</div>
				<div class="card-body">
					<template v-if="listado==1">
						<div class="md-layout">
							<div class="md-layout-item">
								<md-field md-clearable>
									<label>Qué desea Buscar</label>
									<md-input v-model="buscar" @keypress="listarFdservicio(1,buscar,criterio)"></md-input>
								</md-field>
							</div>&nbsp;&nbsp;&nbsp;
							<div class="md-layout-item">
								<br/> <md-button class="md-icon-button md-dense md-raised md-primary" @click="listarFdservicio(1,buscar,criterio)">
								<md-icon>search</md-icon>
								</md-button>
							</div>
						</div>
						<div class="table-responsive">
							<table class="table table-bordered table-striped table-sm">
								<thead>
									<tr class="p-3 mb-2 bg-dark text-white">                            
										<th>Nombre</th>
										<!-- <th>Oficina</th> -->
										<th>Servicio</th>
										<th>Valor</th>
										<th>Descripción</th>
										<th>Estado</th>
										<th>Opciones</th>
									</tr>
								</thead>
								<tbody>
									<tr  v-for="fdservicio in arrayFac_det_servicio" :key="fdservicio.id" >                            
										<td v-text="fdservicio.nombre"></td>
										<td v-text="fdservicio.nomSer"></td>																
										<td v-text="fdservicio.valor"></td>																
										<td v-text="fdservicio.desc"></td>																															
										<td>
                                            <div v-if="fdservicio.edo">
                                                <span class="badge badge-success">Activo</span>
                                            </div>
                                            <div v-else>
                                                <span class="badge badge-danger">Desactivado</span>
                                            </div> 
                                        </td>
										<!-- <td v-text="fdservicio.nomOfi"></td>																 -->
										<td>
											<md-button class="md-icon-button " @click="mostrarActualizar(fdservicio)" title="Actualizar">                         
												<i class="material-icons Color3">edit</i>
											</md-button>	
											<!-- <md-button class="md-icon-button md-primary " @click="eliminarFdservicio(fdservicio)" title="Eliminar">
												<i class="material-icons Color4">delete</i>
											</md-button> -->
                                            <template v-if="fdservicio.edo">
                                                <md-button type="button" class="md-icon-button" @click="desactivarFdservicio(fdservicio.id)">
                                                    <i class="material-icons Color4">highlight_off</i>
                                                </md-button>
                                            </template>
                                            <template v-else>
                                                 <md-button type="button" class="md-icon-button" @click="activarFdservicio(fdservicio.id)">
                                                    <i class="material-icons Color3">check_circle_outline</i>
                                                </md-button>
                                            </template>
										</td>									
									</tr>
								</tbody>
							</table>
						</div>
							<nav>
								<ul class="pagination justify-content-center">
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
					</template>
					<template v-else-if="listado==0">
							<div class="card-body">
								<form action method="post" enctype="multipart/form-data" class="form-horizontal">
									<md-card-content>
										<div class="md-layout">	

		
												<div class="md-layout-item">
													<md-field :class="getValidationClass('valor')">
													<label for="valor">Valor</label>
													<span class="md-prefix">
														<i class="material-icons">attach_money</i> 
													</span>
													<md-input type="number" id="valor" name="valor" autocomplete="given-name" v-model="form.valor" :disabled="sending" />
													<span class="md-error" v-if="!$v.form.valor.required">El valor es requerido</span>																																	               
												</md-field>
												</div>&nbsp;&nbsp;&nbsp;
                                            <div class="md-layout-item md-size-100">
											    <md-field :class="getValidationClass('desc')">
												<label for="desc">Descripción</label>
												<md-input type="text" id="desc" name="desc" autocomplete="given-name" v-model="form.desc" :disabled="sending" />
												<span class="md-error" v-if="!$v.form.desc.required">La descripción es requerida</span>																																	               
                                        	</md-field>
										    </div>
										</div>
									</md-card-content>
								</form>
							</div>
							<div class="modal-footer">
								<md-card-actions>
								<md-button type="button" class="md-raised" @click="soapRequest()">pagar</md-button>
									<md-button type="submit" v-if="tipoAccion==1" class="md-dense md-raised md-primary" :disabled="sending" @click="validarDatos()">Guardar</md-button>
									<md-button type="submit" v-if="tipoAccion==2" class="md-dense md-raised md-primary" :disabled="sending" @click="actualizarFdservicio()">Actualizar</md-button>
								</md-card-actions>
							</div>
					</template>
				</div>
			</div>
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
	data() {
		return {

		id_fac_servicio:"",

		form: {
			nombre:" ",
			valor: "50000",
			desc: " ",
			// edo: "",
			// id_servicio: "",
			// id_oficina: "",
		},
		id_oficina:0,
		id_servicio:0,
		idFacSer:0,
		tipoAccion: 1,
		listado: 0,
		sending: false,
		arrayOficina: [],
		arrayFac_servicio: [],
		id_servicio: 0,
		nomSER:"  ",
		arrayFac_det_servicio:[],
		id_fdservicio:0,
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
		criterio: "Nombre",
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
			valor: {
				required
			},
			desc: {
				required
			},
			// id_oficina: {
			// 	required
			// },
			// id_servicio: {
			// 	required
			// },

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
		soapRequest(){
			var str = '<?xml versión="1.0" encoding="utf-8" ?>'+
			'<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/"'+
			'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"'+
			'xmlns:xsd="http://www.w3.org/2001/XMLSchema">'+
			'<soap:Header>'+
			'<messageHeader xmlns="http://www.uc-council.org/smp/schemas/eanucc">'+
			'<to>'+
			'<gln>789123</gln>'+
			'</to>'+
			'<from>'+
			'<gln>123456</gln>'+
			'</from>'+
			'<representingParty>'+
			'<gln>123456</gln>'+
			'</representingParty>'+
			'</messageHeader>'+
			'</soap:Header>'+
			'<soap:Body wsu:Id="Id-83c96eeb-8ff9-4b18-8bb5-8fe46fd664a5"'+
			'xmlns:wsu="http://schemas.xmlsoap.org/ws/2002/07/utility">'+
			'<getBankList xmlns="http://www.uc-council.org/smp/schemas/eanucc">'+
			'<getBankListInformation>'+
			'<entityCode>8301402061</entityCode>'+
			'</getBankListInformation>'+
			'</getBankList>'+
			'</soap:Body>'+
			'</soap:Envelope>';

						function createCORSRequest(method, url) {
												var xhr = new XMLHttpRequest();
												if ("withCredentials" in xhr) {
														xhr.open(method, url, false);
												} else if (typeof XDomainRequest != "undefined") {
														alert
														xhr = new XDomainRequest();
														xhr.open(method, url);
												} else {
														console.log("CORS not supported");
														alert("CORS not supported");
														xhr = null;
												}
												return xhr;
										}
						var xhr = createCORSRequest("POST", "https://200.1.124.62/PSEHostingUI/GetBankListWS.aspx?enc=");
						if(!xhr){
						console.log("XHR issue");
						return;
						}

						xhr.onload = function (){
						var results = xhr.responseText;
						console.log(results);
						}

						xhr.setRequestHeader('Content-Type', 'text/xml');
						xhr.send(str);
		},
		validarDatos() {
			this.$v.$touch();
			if (!this.$v.$invalid) {
				this.registrarFdservicio();
				this.clearForm();
			}
		},
		clearForm() {
			this.$v.$reset();
			this.form.nombre=null;
			this.form.valor=null;
			this.form.desc=null;
			this.form.edo=null;
			this.id_servicio=null;
			// this.id_oficina="";
		},
		listarFdservicio(page, buscar, criterio) {
            let me = this;
            var url = "/fdservicio?page=" + page + "&buscar=" + buscar + "&criterio=" + criterio;
            axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayFac_det_servicio = respuesta.fac_det_servicio.data;
                    me.pagination = respuesta.pagination;
                }).catch(function (error) {
                    console.log(error);
            });
		},
		// selectOficina() {
        //     let me = this;
        //     var url = "oficina/selectOficina";
        //     axios.get(url).then(function (response) {
        //             var respuesta = response.data;
        //             me.arrayOficina = respuesta.oficina;
        //         }).catch(function (error) {
        //             console.log(error);
        //     });
        // },
		selectFac_servicio() {
            let me = this;
            var url = "fac_servicio/selectFac_servicio";
            axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayFac_servicio = respuesta.fac_servicios;
                }).catch(function (error) {
                    console.log(error);
            });
        },
		cambiarPagina(page, buscar, criterio) {
			let me = this;
			me.pagination.current_page = page;
			me.listarFdservicio(page, buscar, criterio);
		},
		mostraFormulario() {
			this.clearForm();
			let me = this;
			(this.tipoAccion = 1), (me.listado = 0);
		},
		cerrarFormulario() {
			this.listado = 1;
			this.listarFdservicio(1, this.buscar, this.criterio);
        },
        desactivarFdservicio(id){
               swal({
                title: 'Esta seguro de desactivar este detalle de factura?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar!',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me = this;

                    axios.put('/fdservicio/desactivar',{
                        'id': id
                    }).then(function (response) {
                        me.listarFdservicio(1,'','nombre');
                        swal(
                        'Desactivado!',
                        'El registro ha sido desactivado con éxito.',
                        'success'
                        )
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
        activarFdservicio(id){
               swal({
                title: 'Esta seguro de activar este detalle de factura?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar!',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me = this;

                    axios.put('/fdservicio/activar',{
                        'id': id
                    }).then(function (response) {
                        me.listarFdservicio(1,'','nombre');
                        swal(
                        'Activado!',
                        'El registro ha sido activado con éxito.',
                        'success'
                        )
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
		registrarFdservicio() {
		let me = this;
            axios.post("/fdservicio/registrar", {
					'nombre': this.form.nombre.toUpperCase(),
                    'valor': this.form.valor,
                    'desc': this.form.desc.toUpperCase(),
                    'edo': this.form.edo,
					// 'id_oficina': this.id_oficina,
					'id_servicio': this.id_servicio,
                }).then(function (response) {
                    me.cerrarFormulario();
					me.listarFdservicio(1, "", "nombre");
					me.mensaje("Guardado", "Guardo ");
                }).catch(function (error) {
                console.log(error);
                });
		},
		mostrarActualizar(data = []) {
			let me = this;
			(this.tipoAccion = 2), (me.listado = 0);
			(this.id_fdservicio = data["id"]);
			this.form.nombre = data["nombre"];
			this.form.valor = data["valor"];
			this.form.desc = data["desc"];
			this.form.edo = data["edo"];
			// this.id_oficina = data["id_oficina"]; 
			this.id_servicio = data["id_servicio"]; 
		},
		actualizarFdservicio() {
		let me = this;
            axios.put("/fdservicio/actualizar", {
					'id':this.id_fdservicio,
                    'nombre': this.form.nombre.toUpperCase(),
                    'valor': this.form.valor,
                    'desc': this.form.desc.toUpperCase(),
                    'edo': this.form.edo,
					// 'id_oficina': this.id_oficina,
					'id_servicio': this.id_servicio,
            })
            .then(function (response) {
                me.cerrarFormulario();
                me.listarFdservicio(1, "", "nombre");
                me.mensaje("Actualizado", "Actualizo");
            })
            .catch(function (error) {
                console.log(error);
            });
		},
		// eliminarFdservicio(data = []) {
		// 	swal({
		// 		title: "¿Esta seguro de Eliminar el servicio " + data["nombre"],
		// 		type: "warning",
		// 		title: "¿Estás seguro?",
		// 		text: "¡No podrás revertir esto!",
		// 		showCancelButton: true,
		// 		confirmButtonColor: "#3085d6",
		// 		cancelButtonColor: "#d33",
		// 		cancelButtonText: "¡No, cancélalo!",
		// 		confirmButtonText: "¡Si, bórralo!"
		// 	}).then(result => {
		// 		if (result.value) {
		// 		let me = this;
		// 		this.id_fdservicio = data["id"];
		// 		axios
		// 			.post("/fdservicio/eliminar", {
		// 			id: this.id_fdservicio
		// 			})
		// 			.then(function(response) {
		// 				me.listarFdservicio(1, "", "nombre");
					
		// 			me.mensaje("Eliminado", "Eliminó ");
		// 			})
		// 			.catch(function(error) {
		// 			console.log(error);
		// 			});
		// 		} else if (
		// 		// Read more about handling dismissals
		// 		result.dismiss === swal.DismissReason.cancel
		// 		) {
		// 		}
		// 	});
		// },
		
		mensaje(tipo, crud) {
			swal(tipo, "El registro se " + crud + " con éxito.", "success");
		}
	},
	mounted() {
		// this.selectOficina();
		this.selectFac_servicio();
		this.listarFdservicio(1, this.buscar, this.criterio);
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