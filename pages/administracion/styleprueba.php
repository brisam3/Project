<style>
   
    #tablaRendiciones, #tablaRendiciones th, #tablaRendiciones td {
        color: #000; /* Negro sólido */
    }
    
    #tablaRendiciones input[type="number"] {
        color: #000 !important; /* Negro para los inputs */
    }
    .table-responsive {
        overflow-x: auto;
    }
    #tablaRendiciones {
        font-size: 0.85rem; /* Reducido ligeramente para mejor proporción */
        width: 100%;
        border-spacing: 0;
    }
    #tablaRendiciones th, #tablaRendiciones td {
        padding: 0.45rem; /* Tamaño ajustado */
        vertical-align: middle;
        border: 1px solid #dee2e6;
    }
    #tablaRendiciones thead th {
        background-color: rgb(0, 0, 0);
        color: white;
        font-weight: bold;
        text-align: center;
        white-space: nowrap;
    }
    #tablaRendiciones tbody tr:nth-of-type(odd) {
        background-color: rgba(0, 0, 0, 0.05);
    }
    #tablaRendiciones tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.075);
    }
    .table-input {
        width: 100%;
        padding: 0.2rem; /* Ajustado para coincidir con el nuevo diseño */
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        text-align: right;
    }
    .total-column, .neto-column, .total-neto-global {
        font-weight: bold;
        background-color: #f8f9fa;
    }
    #filaTotalNeto {
        background-color: rgb(221, 223, 224);
    }
    
    #filaTotalNeto td {
        font-weight: bold;
    }
    /* Estilos modernos para los inputs */
    #tablaRendiciones input[type="number"] {
        width: 100%;
        padding: 0.3rem; /* Ajustado para mantener consistencia */
        border: none;
        transition: all 0.3s ease;
        background-color: transparent;
        color: #140f07;
    }
    

    #tablaRendiciones input[type="number"]:focus {
        outline: none;
        border-bottom-color: #1d274d;
        box-shadow: 0 1px 0 0 #1d274d;
    }
    table.datatables-ajax {
    color: black; /* Establece el color de la letra en negro */
    }   
    #miTabla {
    color: black !important; /* Establece el color de la letra en negro para toda la tabla */
}

#miTabla td.font-weight-bold, #miTabla th {
    color: black !important; /* Fuerza el color negro para las celdas con la clase font-weight-bold */
}
#miTabla input[type="number"] {
        width: 100%;
        padding: 0.3rem; /* Ajustado para mantener consistencia */
        border: none;
        transition: all 0.3s ease;
        background-color: transparent;
        color: #140f07;
    }
    

    #miTabla input[type="number"]:focus {
        outline: none;
        border-bottom-color: #1d274d;
        box-shadow: 0 1px 0 0 #1d274d;
    }



    
</style>
