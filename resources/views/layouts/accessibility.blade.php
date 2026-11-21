<style>
        /* Hallmark · pre-emit critique: P4 H4 E4 S4 R5 V3 */
        :root {
            --huasteca-verde-activo: #04563f;
            --huasteca-peligro-texto: #b02a37;
            --huasteca-secundario-texto: #565e64;
            --huasteca-info-texto: #055160;
            --huasteca-foco: #153b80;
        }
        html, body {
            max-width: 100%;
            overflow-x: clip;
        }
        main, main .row > *, main .d-flex > * {
            min-width: 0;
        }
        h1, h2, h3, h4, h5, h6 {
            min-width: 0;
            overflow-wrap: anywhere;
        }
        .badge {
            max-width: 100%;
            white-space: normal;
            overflow-wrap: anywhere;
        }
        .btn, .nav-link {
            white-space: nowrap;
        }
        .btn-outline-danger {
            --bs-btn-color: var(--huasteca-peligro-texto);
            --bs-btn-border-color: var(--huasteca-peligro-texto);
            --bs-btn-hover-bg: var(--huasteca-peligro-texto);
            --bs-btn-hover-border-color: var(--huasteca-peligro-texto);
        }
        .btn-outline-secondary {
            --bs-btn-color: var(--huasteca-secundario-texto);
            --bs-btn-border-color: var(--huasteca-secundario-texto);
        }
        .btn-outline-info {
            --bs-btn-color: var(--huasteca-info-texto);
            --bs-btn-border-color: var(--huasteca-info-texto);
            --bs-btn-hover-bg: var(--huasteca-info-texto);
            --bs-btn-hover-border-color: var(--huasteca-info-texto);
            --bs-btn-hover-color: var(--huasteca-blanco);
        }
        .table-responsive, .form-control, .form-select {
            min-width: 0;
            max-width: 100%;
        }
        .navbar .navbar-brand {
            min-width: 0;
            max-width: calc(100% - 4rem);
            margin-right: .5rem;
        }
        .navbar-brand img, .navbar-toggler {
            flex-shrink: 0;
        }
        .navbar-brand .brand-title {
            font-size: clamp(.875rem, 3vw, 1.25rem);
        }
        .navbar-collapse, .navbar-collapse > .d-flex > span {
            min-width: 0;
        }
        .navbar-collapse > .d-flex > span {
            max-width: 12rem;
            overflow-wrap: anywhere;
        }
        .visually-hidden-focusable {
            z-index: 2000;
        }
        a:focus-visible, button:focus-visible, input:focus-visible,
        select:focus-visible, textarea:focus-visible, .btn:focus-visible,
        .nav-link:focus-visible, .navbar-toggler:focus-visible {
            outline: 3px solid var(--huasteca-foco);
            outline-offset: 2px;
            box-shadow: 0 0 0 2px var(--huasteca-blanco);
        }
        @media (max-width: 47.99rem) {
            main .d-flex.justify-content-between {
                flex-wrap: wrap;
                gap: 1rem;
            }
        }
        @media (pointer: coarse) {
            .btn, .navbar-toggler {
                min-height: 44px;
            }
        }
</style>
