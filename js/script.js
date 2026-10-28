(() => {
    const menuToggle = document.querySelector(".menu-toggle");
    const menuPrincipal = document.getElementById("menu-principal");

    if (menuToggle && menuPrincipal) {
        const cerrarMenu = () => {
            menuPrincipal.classList.remove("abierto");
            menuToggle.setAttribute("aria-expanded", "false");
            menuToggle.setAttribute("aria-label", "Abrir menú de navegación");
            menuToggle.querySelector("i").className = "fa-solid fa-bars";
        };

        menuToggle.addEventListener("click", () => {
            const abierto = menuToggle.getAttribute("aria-expanded") !== "true";
            menuPrincipal.classList.toggle("abierto", abierto);
            menuToggle.setAttribute("aria-expanded", String(abierto));
            menuToggle.setAttribute(
                "aria-label",
                abierto ? "Cerrar menú de navegación" : "Abrir menú de navegación"
            );
            menuToggle.querySelector("i").className = abierto
                ? "fa-solid fa-xmark"
                : "fa-solid fa-bars";
        });

        menuPrincipal.querySelectorAll("a").forEach((enlace) => {
            enlace.addEventListener("click", cerrarMenu);
        });

        document.addEventListener("keydown", (evento) => {
            if (evento.key === "Escape") {
                cerrarMenu();
            }
        });

        window.addEventListener("resize", () => {
            if (window.innerWidth > 768) {
                cerrarMenu();
            }
        });
    }

    if (document.body.dataset.sesionAutenticada === "true") {
        window.addEventListener("pageshow", (evento) => {
            if (evento.persisted) {
                window.location.reload();
            }
        });
    }

    const avisoPerfil = document.querySelector(".perfil-notificacion");
    if (avisoPerfil) {
        window.setTimeout(() => {
            avisoPerfil.classList.add("ocultando");
            window.setTimeout(() => avisoPerfil.remove(), 500);
        }, 3000);
    }

    const mostrarAvisoPerfil = (mensaje, tipo) => {
        document.querySelector(".perfil-notificacion")?.remove();
        const aviso = document.createElement("p");
        aviso.className = `perfil-aviso perfil-notificacion ${tipo}`;
        aviso.setAttribute("role", "status");
        aviso.textContent = mensaje;
        document.body.append(aviso);

        window.setTimeout(() => {
            aviso.classList.add("ocultando");
            window.setTimeout(() => aviso.remove(), 500);
        }, 3000);
    };

    const formPerfil = document.getElementById("form-perfil");
    if (formPerfil) {
        formPerfil.addEventListener("submit", (evento) => {
            const password = formPerfil.querySelector("[name='password']");
            const confirmar = formPerfil.querySelector("[name='confirmar']");
            if (
                (password?.value || confirmar?.value) &&
                !window.confirm("¿Seguro que querés cambiar la contraseña?")
            ) {
                evento.preventDefault();
                return;
            }

            evento.preventDefault();
            const botonGuardar = formPerfil.querySelector("button[type='submit']");
            if (botonGuardar) {
                botonGuardar.disabled = true;
            }

            fetch(formPerfil.action, {
                method: "POST",
                body: new FormData(formPerfil),
                headers: { "X-Requested-With": "XMLHttpRequest" }
            })
                .then(async (respuesta) => {
                    const datos = await respuesta.json();
                    if (!respuesta.ok || !datos.ok) {
                        throw new Error(datos.mensaje || "No se pudieron guardar los cambios.");
                    }

                    const nombre = document.getElementById("perfil-nombre");
                    const email = document.getElementById("perfil-email");
                    const telefono = document.getElementById("perfil-telefono");
                    const direccion = document.getElementById("perfil-direccion");
                    if (nombre) nombre.textContent = `${datos.usuario.nombre} ${datos.usuario.apellido}`;
                    if (email) email.textContent = datos.usuario.email;
                    if (telefono) telefono.textContent = datos.usuario.telefono;
                    if (direccion) direccion.textContent = datos.usuario.direccion;
                    formPerfil.querySelector("[name='password']").value = "";
                    formPerfil.querySelector("[name='confirmar']").value = "";
                    mostrarAvisoPerfil(datos.mensaje, datos.tipo);
                })
                .catch((error) => {
                    mostrarAvisoPerfil(error.message, "error");
                })
                .finally(() => {
                    if (botonGuardar) {
                        botonGuardar.disabled = false;
                    }
                });
        });
    }

    const btnSubir = document.getElementById("btnSubir");
    if (btnSubir) {
        window.addEventListener("scroll", () => {
            btnSubir.classList.toggle("mostrar", window.scrollY > 400);
        });
    }

    const categorias = document.querySelectorAll(".categoria");
    if (categorias.length > 0) {
        if ("IntersectionObserver" in window) {
            const observerCategorias = new IntersectionObserver((entradas, observer) => {
                entradas.forEach((entrada) => {
                    if (entrada.isIntersecting) {
                        entrada.target.classList.add("mostrar");
                        observer.unobserve(entrada.target);
                    }
                });
            });
            categorias.forEach((categoria) => observerCategorias.observe(categoria));
        } else {
            categorias.forEach((categoria) => categoria.classList.add("mostrar"));
        }
    }

    const tarjetasNosotros = document.querySelectorAll(".nosotros-card");
    const contenedorNosotros = document.querySelector(".contenedor-nosotros");
    if (tarjetasNosotros.length > 0 && contenedorNosotros) {
        if ("IntersectionObserver" in window) {
            const observerNosotros = new IntersectionObserver((entradas, observer) => {
                if (entradas[0].isIntersecting) {
                    tarjetasNosotros.forEach((tarjeta, indice) => {
                        window.setTimeout(() => tarjeta.classList.add("mostrar"), indice * 350);
                    });
                    observer.disconnect();
                }
            }, { threshold: 0.4 });
            observerNosotros.observe(contenedorNosotros);
        } else {
            tarjetasNosotros.forEach((tarjeta) => tarjeta.classList.add("mostrar"));
        }
    }

    const slides = document.querySelectorAll(".slide");
    if (slides.length > 1) {
        let actual = 0;
        window.setInterval(() => {
            slides[actual].classList.remove("activo");
            actual = (actual + 1) % slides.length;
            slides[actual].classList.add("activo");
        }, 3000);
    }

    const frase = document.getElementById("frase");
    if (frase) {
        const texto = "Cada receta guarda una historia, cada aroma crea un recuerdo.";
        let indice = 0;
        const escribir = () => {
            if (indice < texto.length) {
                frase.textContent += texto.charAt(indice);
                indice += 1;
                window.setTimeout(escribir, 50);
            }
        };

        if ("IntersectionObserver" in window) {
            const observerFrase = new IntersectionObserver((entradas, observer) => {
                if (entradas[0].isIntersecting) {
                    escribir();
                    observer.disconnect();
                }
            }, { threshold: 0.5 });
            observerFrase.observe(frase);
        } else {
            escribir();
        }
    }

    const filtrosCategoria = document.querySelectorAll(".categorias-productos button");
    const productos = document.querySelectorAll(".card-producto");
    const mensajeSinProductos = document.getElementById("sin-productos-categoria");

    const aplicarFiltroCategoria = (boton) => {
        const categoria = boton.dataset.categoria;
        let visibles = 0;

        filtrosCategoria.forEach((item) => {
            item.classList.toggle("activo", item === boton);
        });

        productos.forEach((producto) => {
            producto.hidden = categoria !== "todas" && producto.dataset.categoria !== categoria;
            if (!producto.hidden) {
                visibles += 1;
            }
        });

        if (mensajeSinProductos) {
            mensajeSinProductos.hidden = visibles > 0;
        }
    };

    filtrosCategoria.forEach((boton) => {
        boton.addEventListener("click", () => aplicarFiltroCategoria(boton));
    });

    const categoriaSolicitada = new URLSearchParams(window.location.search)
        .get("categoria")
        ?.toLowerCase();
    if (categoriaSolicitada) {
        const filtroSolicitado = [...filtrosCategoria].find(
            (boton) => boton.dataset.categoriaSlug === categoriaSolicitada
        );
        if (filtroSolicitado) {
            aplicarFiltroCategoria(filtroSolicitado);
        }
    }

    const mensajeCarrito = document.getElementById("mensaje-carrito");
    document.querySelectorAll(".productos-grid form").forEach((formulario) => {
        formulario.addEventListener("submit", async (evento) => {
            evento.preventDefault();
            const boton = formulario.querySelector("button[type='submit']");
            if (!boton || !mensajeCarrito) {
                return;
            }

            boton.disabled = true;
            try {
                const respuesta = await fetch(formulario.action, {
                    method: "POST",
                    body: new FormData(formulario),
                    headers: {
                        "Accept": "application/json",
                        "X-Requested-With": "XMLHttpRequest"
                    }
                });
                const datos = await respuesta.json();
                mensajeCarrito.textContent = datos.message || "No se pudo agregar el producto.";
                mensajeCarrito.classList.toggle("error", !respuesta.ok);
                mensajeCarrito.hidden = false;
                window.setTimeout(() => {
                    mensajeCarrito.hidden = true;
                }, 3500);
            } catch (error) {
                console.error("Error al agregar el producto al carrito:", error);
                mensajeCarrito.textContent = "No se pudo agregar el producto. Intentá nuevamente.";
                mensajeCarrito.classList.add("error");
                mensajeCarrito.hidden = false;
            } finally {
                boton.disabled = false;
            }
        });
    });

    document.addEventListener("click", (evento) => {
        const objetivo = evento.target instanceof Element
            ? evento.target.closest("[data-seccion], [data-abrir-modal], [data-cerrar-modal], [data-confirm]")
            : null;
        if (!objetivo) {
            return;
        }

        if (objetivo.dataset.confirm && !window.confirm(objetivo.dataset.confirm)) {
            evento.preventDefault();
            return;
        }

        if (objetivo.dataset.seccion) {
            document.querySelectorAll(".admin-seccion, .pestana").forEach((elemento) => {
                elemento.classList.remove("activa");
            });
            const seccion = document.getElementById(objetivo.dataset.seccion);
            if (seccion) {
                seccion.classList.add("activa");
            }
            objetivo.classList.add("activa");
        }

        const modalId = objetivo.dataset.abrirModal || objetivo.dataset.cerrarModal;
        if (modalId) {
            evento.preventDefault();
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.style.display = objetivo.dataset.abrirModal ? "flex" : "none";
            }
        }
    });

    const mensajeAdmin = document.querySelector(".admin-notificacion");
    if (mensajeAdmin) {
        window.setTimeout(() => {
            mensajeAdmin.style.transition = "opacity 0.5s ease";
            mensajeAdmin.style.opacity = "0";
            window.setTimeout(() => mensajeAdmin.remove(), 500);
        }, 3000);
    }

})();