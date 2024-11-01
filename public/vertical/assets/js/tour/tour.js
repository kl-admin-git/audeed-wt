$(document).ready(function () {
    const rutaInicial = window.location.href.split("/")[3];
    let tipo = "";
    // Instance the tour
    const tour = new Shepherd.Tour({
        defaultStepOptions: {
            cancelIcon: {
                enabled: false,
                // classes: 'shepherd-theme-arrows',
            },
            scrollTo: { behavior: "smooth", block: "center" },
        },
    });
    /**
     * esta funcion se encarga de la configuracion del tour de la administracion
     * @param {*} tipo
     */
    const iniciarAdminitracion = (tipo, activacion) => {
      
        const interno = tipo.split("/")[1];

        if (interno === "empresas") {
            if (activacion === true) {
                return;
            }
            tour.addStep({
                title: "Creacion empresas",
                text: `
                    texto para descripcion de la accion <br>\
                    <a href="#!" src="/vertical/assets/images/tour-img/creacion_empresa.png" class="ver-imagen-video" tipo="imagen"> ver imagen <a/>
                `,
                attachTo: {
                    element: "#crearEmpresa",
                    on: "bottom",
                },
                buttons: [
                    {
                        action() {
                            guardarTutorial(tour);
                        },
                        classes: "shepherd-button-secondary",
                        text: "Omitir",
                    },

                    {
                        action() {
                            return this.next();
                        },
                        text: "Siguiente",
                    },
                    // {
                    //   text: 'Atras',
                    //   action: tour.back
                    // },
                    // {
                    //   text: 'Siguiente',
                    //   action: tour.next
                    // }
                ],

                // id: 'creating'
            });

            tour.addStep({
                title: "Busqueda empresas",
                text: `
                texto para descripcion de la accion <br>\
                <a href="#!" src="/vertical/assets/images/tour-img/buscar_empresa.png" class="ver-imagen-video" tipo="imagen"> ver imagen <a/>
                `,
                attachTo: {
                    element: "#buscar-tour",
                    on: "left",
                },
                buttons: [
                    {
                        action() {
                            guardarTutorial(tour);
                        },
                        classes: "shepherd-button-secondary",
                        text: "Omitir",
                    },
                    {
                        action() {
                            return this.back();
                        },
                        classes: "shepherd-button-secondary",
                        text: "Atras",
                    },
                    {
                        action() {
                            return this.next();
                        },
                        text: "Siguiente",
                    },
                ],

                // id: 'creating'
            });

            tour.addStep({
                title: "Acciones",
                text: `Creating a Shepherd tour is easy. too!\
        Just create a \`Tour\` instance, and add as many steps as you want.`,
                attachTo: {
                    element: "#acciones-tour",
                    on: "left",
                },
                buttons: [
                    {
                        action() {
                            guardarTutorial(tour);
                        },
                        classes: "shepherd-button-secondary",
                        text: "Omitir",
                    },
                    {
                        action() {
                            return this.back();
                        },
                        classes: "shepherd-button-secondary",
                        text: "Atras",
                    },
                    {
                        action() {
                            return this.next();
                        },
                        text: "Siguiente",
                    },
                    // {
                    //   text: 'Atras',
                    //   action: tour.back
                    // },
                    // {
                    //   text: 'Siguiente',
                    //   action: tour.next
                    // }
                ],

                // id: 'creating'
            });

            tour.addStep({
                title: "Cambio de estado",
                text: `Creating a Shepherd tour is easy. too!\
        Just create a \`Tour\` instance, and add as many steps as you want.`,
                attachTo: {
                    element: "#estado-tour",
                    on: "bottom",
                },
                buttons: [
                    {
                        action() {
                            guardarTutorial(tour);
                        },
                        classes: "shepherd-button-secondary",
                        text: "Omitir",
                    },
                    {
                        action() {
                            return this.back();
                        },
                        classes: "shepherd-button-secondary",
                        text: "Atras",
                    },
                    {
                        action() {
                            guardarTutorial(tour);
                        },
                        text: "Finalizar",
                    },
                    // {
                    //   text: 'Atras',
                    //   action: tour.back
                    // },
                    // {
                    //   text: 'Siguiente',
                    //   action: tour.next
                    // }
                ],

                // id: 'creating'
            });
        } else if (interno === "establecimiento") {
            if (activacion === true) {
                return;
            }
            tour.addStep({
                title: "Creacion establecimientos",
                text: `
                texto para descripcion de la accion <br>\
                <a href="#!" src="/vertical/assets/images/tour-img/crear_establecimiento.png" class="ver-imagen-video" tipo="imagen"> ver imagen <a/>
            `,
                attachTo: {
                    element: "#crearEstablecimiento",
                    on: "bottom",
                },
                buttons: [
                    {
                        action() {
                            guardarTutorial(tour);
                        },
                        classes: "shepherd-button-secondary",
                        text: "Omitir",
                    },

                    {
                        action() {
                            return this.next();
                        },
                        text: "Siguiente",
                    },
                ],

                // id: 'creating'
            });

            tour.addStep({
                title: "Creacion establecimientos",
                text: `
                texto para descripcion de la accion <br>\
                <a href="#!" src="/vertical/assets/images/tour-img/buscar_establecimiento.png" class="ver-imagen-video" tipo="imagen"> ver imagen <a/>
            `,
                attachTo: {
                    element: "#buscar-tour",
                    on: "bottom",
                },
                buttons: [
                    {
                        action() {
                            guardarTutorial(tour);
                        },
                        classes: "shepherd-button-secondary",
                        text: "Omitir",
                    },
                    {
                        action() {
                            return this.back();
                        },
                        text: "Atras",
                    },
                    {
                        action() {
                            return this.next();
                        },
                        text: "Siguiente",
                    },
                ],

                // id: 'creating'
            });
            tour.addStep({
                title: "Creacion establecimientos",
                text: `Creating a Shepherd tour is easy. too!\
        Just create a \`Tour\` instance, and add as many steps as you want.`,
                attachTo: {
                    element: "#acciones-tour",
                    on: "bottom",
                },
                buttons: [
                    {
                        action() {
                            guardarTutorial(tour);
                        },
                        classes: "shepherd-button-secondary",
                        text: "Omitir",
                    },
                    {
                        action() {
                            return this.back();
                        },
                        text: "Atras",
                    },
                    {
                        action() {
                            return this.next();
                        },
                        text: "Siguiente",
                    },
                ],

                // id: 'creating'
            });

            tour.addStep({
                title: "Creacion establecimientos",
                text: `Creating a Shepherd tour is easy. too!\
        Just create a \`Tour\` instance, and add as many steps as you want.`,
                attachTo: {
                    element: "#acciones-estado",
                    on: "bottom",
                },
                buttons: [
                    {
                        action() {
                            guardarTutorial(tour);
                        },
                        classes: "shepherd-button-secondary",
                        text: "Omitir",
                    },
                    {
                        action() {
                            return this.back();
                        },
                        text: "Atras",
                    },
                    {
                        action() {
                            guardarTutorial(tour);
                        },
                        text: "Finalizar",
                    },
                ],

                // id: 'creating'
            });

            //
        } else if (interno === "usuarios") {
            if (activacion === true) {
                return;
            }
            tour.addStep({
                title: "Creacion establecimientos",
                text: `
                texto para descripcion de la accion <br>\
                    <a href="#!" src="/vertical/assets/images/tour-img/crear_usuario.png" class="ver-imagen-video" tipo="imagen"> ver imagen <a/>
                `,
                attachTo: {
                    element: "#crearUsuario",
                    on: "bottom",
                },
                buttons: [
                    {
                        action() {
                            guardarTutorial(tour);
                        },
                        classes: "shepherd-button-secondary",
                        text: "Omitir",
                    },

                    {
                        action() {
                            return this.next();
                        },
                        text: "Siguiente",
                    },
                ],

                // id: 'creating'
            });

            tour.addStep({
                title: "Creacion establecimientos",
                text: `
                texto para descripcion de la accion <br>\
                    <a href="#!" src="/vertical/assets/images/tour-img/buscar_usuario.png" class="ver-imagen-video" tipo="imagen"> ver imagen <a/>
                `,
                attachTo: {
                    element: "#buscar-tour",
                    on: "bottom",
                },
                buttons: [
                    {
                        action() {
                            guardarTutorial(tour);
                        },
                        classes: "shepherd-button-secondary",
                        text: "Omitir",
                    },
                    {
                        action() {
                            return this.back();
                        },
                        text: "Atras",
                    },
                    {
                        action() {
                            return this.next();
                        },
                        text: "Siguiente",
                    },
                ],

                // id: 'creating'
            });

            tour.addStep({
                title: "Creacion establecimientos",
                text: `Creating a Shepherd tour is easy. too!\
        Just create a \`Tour\` instance, and add as many steps as you want.`,
                attachTo: {
                    element: "#acciones-tour",
                    on: "bottom",
                },
                buttons: [
                    {
                        action() {
                            guardarTutorial(tour);
                        },
                        classes: "shepherd-button-secondary",
                        text: "Omitir",
                    },
                    {
                        action() {
                            return this.back();
                        },
                        text: "Atras",
                    },
                    {
                        action() {
                            return this.next();
                        },
                        text: "Siguiente",
                    },
                ],

                // id: 'creating'
            });

            tour.addStep({
                title: "Creacion establecimientos",
                text: `Creating a Shepherd tour is easy. too!\
        Just create a \`Tour\` instance, and add as many steps as you want.`,
                attachTo: {
                    element: "#acciones-estado",
                    on: "bottom",
                },
                buttons: [
                    {
                        action() {
                            guardarTutorial(tour);
                        },
                        classes: "shepherd-button-secondary",
                        text: "Omitir",
                    },
                    {
                        action() {
                            return this.back();
                        },
                        text: "Atras",
                    },
                    {
                        action() {
                            guardarTutorial(tour);
                        },
                        text: "Finalizar",
                    },
                ],

                // id: 'creating'
            });
        } else if (interno === "cargos") {
            if (activacion === true) {
                return;
            }
            tour.addStep({
                title: "Creacion cargos",
                text: `
                texto para descripcion de la accion <br>\
                    <a href="#!" src="/vertical/assets/images/tour-img/crear_cargos.png" class="ver-imagen-video" tipo="imagen"> ver imagen <a/>
                `,
                attachTo: {
                    element: "#crearCargo",
                    on: "bottom",
                },
                buttons: [
                    {
                        action() {
                            guardarTutorial(tour);
                        },
                        classes: "shepherd-button-secondary",
                        text: "Omitir",
                    },

                    {
                        action() {
                            return this.next();
                        },
                        text: "Siguiente",
                    },
                ],

                // id: 'creating'
            });

            tour.addStep({
                title: "Creacion establecimientos",
                text: `
                texto para descripcion de la accion <br>\
                    <a href="#!" src="/vertical/assets/images/tour-img/buscar_cargo.png" class="ver-imagen-video" tipo="imagen"> ver imagen <a/>
                `,
                attachTo: {
                    element: "#buscar-tour",
                    on: "bottom",
                },
                buttons: [
                    {
                        action() {
                            guardarTutorial(tour);
                        },
                        classes: "shepherd-button-secondary",
                        text: "Omitir",
                    },
                    {
                        action() {
                            return this.back();
                        },
                        text: "Atras",
                    },
                    {
                        action() {
                            guardarTutorial(tour);
                        },
                        text: "finalizar",
                    },
                ],

                // id: 'creating'
            });
        }

        tour.start();
    };
    /**
     * esta funcion se encarga de la configuracion del tour de la lista de chequeo
     * @param {*} tipo
     */
    const iniciarListaChequeo = (tipo) => {
        if (tipo === "listachequeo/modelos") {
            tour.addStep({
                title: "Notificacion",
                text: `Creating a Shepherd tour is easy. too!\
        Just create a \`Tour\` instance, and add as many steps as you want.`,
                attachTo: {
                    element: "#buscar-tour",
                    on: "bottom",
                },
                buttons: [
                    {
                        action() {
                            guardarTutorial(tour);
                        },
                        classes: "shepherd-button-secondary",
                        text: "Omitir",
                    },

                    {
                        action() {
                            return this.next();
                        },
                        text: "Finalizar",
                    },
                ],

                // id: 'creating'
            });
        } else if (tipo === "listachequeo/mislistas") {
            tour.addStep({
                title: "Notificacion",
                text: `Creating a Shepherd tour is easy. too!\
        Just create a \`Tour\` instance, and add as many steps as you want. ver video`,
                attachTo: {
                    element: "#tour-modelo",
                    on: "bottom",
                },
                buttons: [
                    {
                        action() {
                            guardarTutorial(tour);
                        },
                        classes: "shepherd-button-secondary",
                        text: "Omitir",
                    },

                    {
                        action() {
                            return this.next();
                        },
                        text: "Siguiente",
                    },
                ],

                // id: 'creating'
            });

            tour.addStep({
                title: "Notificacion",
                text: `Creating a Shepherd tour is easy. too!\
        Just create a \`Tour\` instance, and add as many steps as you want. ver video`,
                attachTo: {
                    element: "#tour-nuevo",
                    on: "bottom",
                },
                buttons: [
                    {
                        action() {
                            guardarTutorial(tour);
                        },
                        classes: "shepherd-button-secondary",
                        text: "Omitir",
                    },
                    {
                        action() {
                            return this.back();
                        },
                        text: "Atras",
                    },
                    {
                        action() {
                            return this.next();
                        },
                        text: "Siguiente",
                    },
                ],

                // id: 'creating'
            });
            tour.addStep({
                title: "Notificacion",
                text: `Creating a Shepherd tour is easy. too!\
        Just create a \`Tour\` instance, and add as many steps as you want. ver video`,
                attachTo: {
                    element: "#tour-creadas",
                    on: "bottom",
                },
                buttons: [
                    {
                        action() {
                            guardarTutorial(tour);
                        },
                        classes: "shepherd-button-secondary",
                        text: "Omitir",
                    },
                    {
                        action() {
                            return this.back();
                        },
                        text: "Atras",
                    },
                    {
                        action() {
                            return this.next();
                        },
                        text: "Siguiente",
                    },
                ],

                // id: 'creating'
            });
            tour.addStep({
                title: "Notificacion",
                text: `Creating a Shepherd tour is easy. too!\
        Just create a \`Tour\` instance, and add as many steps as you want. ver video`,
                attachTo: {
                    element: "#tour-ejecutadas",
                    on: "bottom",
                },
                buttons: [
                    {
                        action() {
                            guardarTutorial(tour);
                        },
                        classes: "shepherd-button-secondary",
                        text: "Omitir",
                    },
                    {
                        action() {
                            return this.back();
                        },
                        text: "Atras",
                    },
                    {
                        action() {
                            guardarTutorial(tour);
                        },
                        text: "Finalizar",
                    },
                ],

                // id: 'creating'
            });
        } else if (tipo === "listachequeo/ejecutadas") {
            tour.addStep({
                title: "Busqueda",
                text: `Creating a Shepherd tour is easy. too!\
        Just create a \`Tour\` instance, and add as many steps as you want. ver video`,
                attachTo: {
                    element: "#buscar-tour",
                    on: "bottom",
                },
                buttons: [
                    {
                        action() {
                            guardarTutorial(tour);
                        },
                        classes: "shepherd-button-secondary",
                        text: "Omitir",
                    },

                    {
                        action() {
                            guardarTutorial(tour);
                        },
                        text: "Finalizar",
                    },
                ],

                // id: 'creating'
            });
        } else if (tipo === "listachequeo/planaccion") {
            tour.addStep({
                title: "plan de accion",
                text: `Creating a Shepherd tour is easy. too!\
        Just create a \`Tour\` instance, and add as many steps as you want. ver video`,
                attachTo: {
                    element: "#tablaPlanAccion",
                    on: "bottom",
                },
                buttons: [
                    {
                        action() {
                            guardarTutorial(tour);
                        },
                        classes: "shepherd-button-secondary",
                        text: "Omitir",
                    },

                    {
                        action() {
                            return this.next();
                        },
                        text: "Siguiente",
                    },
                ],

                // id: 'creating'
            });
            tour.addStep({
                title: "plan de accion",
                text: `
                    texto para descripcion de la accion <br>\
                    <a href="#!" src="/vertical/assets/images/tour-img/buscar_planaccion.png" class="ver-imagen-video" tipo="imagen"> ver imagen <a/>
                `,
                attachTo: {
                    element: "#buscar-tour",
                    on: "bottom",
                },
                buttons: [
                    {
                        action() {
                            guardarTutorial(tour);
                        },
                        classes: "shepherd-button-secondary",
                        text: "Omitir",
                    },
                    {
                        action() {
                            return this.back();
                        },
                        text: "Atras",
                    },
                    {
                        action() {
                            guardarTutorial(tour);
                        },
                        text: "Finalizar",
                    },
                ],

                // id: 'creating'
            });
        }

        tour.start();
    };

    const iniciarInformes = (tipo) => {
        if (tipo === "informes/ejecutadas") {
            tour.addStep({
                title: "plan de accion",
                text: `Creating a Shepherd tour is easy. too!\
        Just create a \`Tour\` instance, and add as many steps as you want. ver video`,
                attachTo: {
                    element: "#tablaInformesEjecutadas",
                    on: "bottom",
                },
                buttons: [
                    {
                        action() {
                            guardarTutorial(tour);
                        },
                        classes: "shepherd-button-secondary",
                        text: "Omitir",
                    },

                    {
                        action() {
                            return this.next();
                        },
                        text: "Siguiente",
                    },
                ],

                // id: 'creating'
            });
            tour.addStep({
                title: "plan de accion",
                text: `Creating a Shepherd tour is easy. too!\
        Just create a \`Tour\` instance, and add as many steps as you want. ver video`,
                attachTo: {
                    element: "#buscar-tour",
                    on: "bottom",
                },
                buttons: [
                    {
                        action() {
                            guardarTutorial(tour);
                        },
                        classes: "shepherd-button-secondary",
                        text: "Omitir",
                    },
                    {
                        action() {
                            return this.back();
                        },
                        text: "Atras",
                    },
                    {
                        action() {
                            guardarTutorial(tour);
                        },
                        text: "Finalizar",
                    },
                ],

                // id: 'creating'
            });
        }

        tour.start();
    };
    /**
     * esta funcion se encarga de la configuracion del tour en el dashboard
     */
    const iniciarDashboard = () => {
        tour.addStep({
            title: "Notificacion",
            text: `Creating a Shepherd tour is easy. too!\
    Just create a \`Tour\` instance, and add as many steps as you want.`,
            attachTo: {
                element: "#dashboard-tour-1",
                on: "bottom",
            },
            buttons: [
                {
                    action() {
                        guardarTutorial(tour);
                    },
                    classes: "shepherd-button-secondary",
                    text: "Omitir",
                },
                {
                    action() {
                        return this.back();
                    },
                    classes: "shepherd-button-secondary",
                    text: "Atras",
                },
                {
                    action() {
                        return this.next();
                    },
                    text: "Siguiente",
                },
                // {
                //   text: 'Atras',
                //   action: tour.back
                // },
                // {
                //   text: 'Siguiente',
                //   action: tour.next
                // }
            ],

            // id: 'creating'
        });

        tour.addStep({
            title: "Menu",
            text: `Creating a Shepherd tour is easy. too!\
    Just create a \`Tour\` instance, and add as many steps as you want.`,
            attachTo: {
                element: "#menu-1",
                on: "left",
            },
            buttons: [
                {
                    action() {
                        guardarTutorial(tour);
                    },
                    classes: "shepherd-button-secondary",
                    text: "Omitir",
                },
                {
                    action() {
                        return this.back();
                    },
                    classes: "shepherd-button-secondary",
                    text: "Atras",
                },
                {
                    action() {
                        return this.next();
                    },
                    text: "Siguiente",
                },
            ],

            // id: 'creating'
        });

        tour.addStep({
            title: "Menu2",
            text: `Creating a Shepherd tour is easy. too!\
    Just create a \`Tour\` instance, and add as many steps as you want.`,
            attachTo: {
                element: "#menu-2",
                on: "left",
            },
            buttons: [
                {
                    action() {
                        guardarTutorial(tour);
                    },
                    classes: "shepherd-button-secondary",
                    text: "Omitir",
                },
                {
                    action() {
                        return this.back();
                    },
                    classes: "shepherd-button-secondary",
                    text: "Atras",
                },
                {
                    action() {
                        return this.next();
                    },
                    text: "Siguiente",
                },
                // {
                //   text: 'Atras',
                //   action: tour.back
                // },
                // {
                //   text: 'Siguiente',
                //   action: tour.next
                // }
            ],

            // id: 'creating'
        });

        tour.addStep({
            title: "menu3",
            text: `Creating a Shepherd tour is easy. too!\
    Just create a \`Tour\` instance, and add as many steps as you want.`,
            attachTo: {
                element: "#menu-3",
                on: "left",
            },
            buttons: [
                {
                    action() {
                        guardarTutorial(tour);
                    },
                    classes: "shepherd-button-secondary",
                    text: "Omitir",
                },
                {
                    action() {
                        return this.back();
                    },
                    classes: "shepherd-button-secondary",
                    text: "Atras",
                },
                {
                    action() {
                        guardarTutorial(tour);
                    },
                    text: "Finalizar",
                },
                // {
                //   text: 'Atras',
                //   action: tour.back
                // },
                // {
                //   text: 'Siguiente',
                //   action: tour.next
                // }
            ],

            // id: 'creating'
        });

        tour.start();
    };

    if (rutaInicial === "dashboard") {
        tipo = rutaInicial;
        $.ajax({
            type: "GET",
            url: `/configuracion/omitir-tour/${usuarioId}`,
            data: {
                tipo,
            },
            dataType: "json",
            success: function (response) {
                if (response.data === true) {
                    return;
                }
                // 1 realizar la verificacion si es la primera vez que ingresa al dashboard

                // 2 Iniciar ocultando el dashboard y mostrar modal con el video de inicio
                $("#tour-dashboard").hide();
                $("#popUpSuscripcion").hide()
                $(".add-tour").append(
                    `<iframe src="https://player.vimeo.com/video/462740184" width="560" height="315" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>`
                );
                $("#modal-tour-video").modal("show");
            },
        });
        // iniciarDashboard(true)
    } else if (rutaInicial === "administracion") {
        
        if (window.location.href.split("/").length !== 5) {
            return
        }
        tipo = `${window.location.href.split("/")[3]}/${
            window.location.href.split("/")[4]
        }`;
        $.ajax({
            type: "GET",
            url: `/configuracion/omitir-tour/${usuarioId}`,
            data: {
                tipo,
            },
            dataType: "json",
            success: function (response) {
                iniciarAdminitracion(tipo, response.data);
            },
        });

        // crearEmpresa
    } else if (rutaInicial === "listachequeo") {
        tipo = `${window.location.href.split("/")[3]}/${
            window.location.href.split("/")[4]
        }`;

        $.ajax({
            type: "GET",
            url: `/configuracion/omitir-tour/${usuarioId}`,
            data: {
                tipo,
            },
            dataType: "json",
            success: function (response) {
                if (tipo === "listachequeo/modelos") {
                    if (response.data === true) {
                        return;
                    }
                    $(".add-tour").append(
                        `<iframe width="560" height="315" src="https://www.youtube.com/embed/M3twMsgT7QI" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Repellat autem, nihil laboriosam commodi itaque, quos officiis quidem exercitationem natus, laborum nostrum dolore eius repudiandae? Iure tempora quis placeat illum sint.</p>
                        `
                    );
                    $("#modal-tour-video").modal("show");
                } else if (tipo === "listachequeo/mislistas") {
                    if (response.data === true) {
                        return;
                    }
                    if (window.location.href.split("/").length !== 5) {
                        return
                    }
                  
                    iniciarListaChequeo(tipo);

                } else if (tipo === "listachequeo/ejecutadas") {
                    if (response.data === true) {
                        return;
                    }
                    $(".add-tour").append(
                        `<iframe width="560" height="315" src="https://www.youtube.com/embed/M3twMsgT7QI" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>`
                    );
                    $("#modal-tour-video").modal("show");
                } else if (tipo === "listachequeo/planaccion") {
                    if (response.data === true) {
                        return;
                    }
                    iniciarListaChequeo(tipo);
                }
            },
        });
    } else if (rutaInicial === "informes") {
        tipo = `${window.location.href.split("/")[3]}/${
            window.location.href.split("/")[4]
        }`;

        $.ajax({
            type: "GET",
            url: `/configuracion/omitir-tour/${usuarioId}`,
            data: {
                tipo,
            },
            dataType: "json",
            success: function (response) {
                if (response.data === true) {
                    return;
                }
                iniciarInformes(tipo);
            },
        });
    }

    /**
     * este evento click se ejecuta solo si en la vista se muestra primero un video de la seccion
     */
    $(document).on("click", ".siguiente-tour", function () {
        if (tipo === "dashboard") {
            iniciarDashboard();
            $("#modal-tour-video").modal("hide");
            $("#tour-dashboard").show();
        } else if (tipo === "listachequeo/modelos") {
            iniciarListaChequeo(tipo);
            $("#modal-tour-video").modal("hide");
        } else if (tipo === "listachequeo/ejecutadas") {
            iniciarListaChequeo(tipo);
            $("#modal-tour-video").modal("hide");
        }
    });
    
    $(document).on("click", ".omitir-tour", function () {

        if (tipo === "dashboard") {
            $("#tour-dashboard").show();
            $("#popUpSuscripcion").show()
        }
        
        $("#modal-tour-video").modal("hide");
        guardarTutorial(tour);
    });

    $(document).on("click", ".ver-imagen-video", function () {
        const tipo = $(this).attr('tipo')
        const src = $(this).attr('src')
        let append ='';

        if (tipo === 'imagen') {
            append += `
                <img src="${src}" style="
                height: auto;
                width: 100%;
                position: relative;
                ">

            `
        }

        const botones= `
            <button type="button"  class="btn btn-secondary waves-effect cerrar" data-dismiss="modal">Cerrar</button>
        `
      
        $('.add-tour').empty()
        $('.add-tour').append(append)
        $('.contenedorBotones').empty()
        $('.contenedorBotones').append(botones)
        $("#modal-tour-video").modal("show");
    });

    const guardarTutorial = (tour) => {
        tour.hide();

        if (tipo === "dashboard") {
            $("#popUpSuscripcion").show()
        }
        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            type: "POST",
            url: "/configuracion/omitir-tour",
            data: {
                tipo,
                usuarioId,
            },
            // enctype: 'multipart/form-data',
            // processData: false,
            // contentType: false,
            cache: false,
            dataType: "json",
            beforeSend: function () {
                // $(".conteneroSpinner").css("display", "flex");
            },
            success: function (data, status, code) {
                // console.log(data)
                // $(".conteneroSpinner").css("display", "none");
            },
            error: function (data, status, code) {
                console.log(code);
                if (data.status == 422) {
                }
            },
        });
    };
});
