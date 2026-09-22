<style>
    body {
        font-size: 12px;
        margin: 0;
    }

    .header {
        top: 10px;
        left: 0;
        right: 0;
        z-index: 10;
        text-align: center;
    }

    .logo {
        display: block;
        width: 300px;
        height: auto;
        margin: auto;
    }

    .container {
        margin-left: 20px;
        margin-right: 20px;
        word-break: break-word;
        overflow-wrap: break-word;
        white-space: normal;
    }

    .border {
        border: 1px solid #000;
    }

    .row {
        width: 100%;
        margin-bottom: 15px;
        clear: both;
    }

    .full-row {
        width: 100%;
        clear: both;
        margin-bottom: 5px;
    }

    .column-3 {
        float: left;
        width: 50%;
    }

    .column-6 {
        float: left;
        width: 46%;
        padding: 0 10px;
    }

    .col-3 {
        float: left;
        width: 20%;
        padding: 0 10px;
    }

    .col-9 {
        float: left;
        width: 60%;
        padding: 0 8px;
    }

    .center {
        text-align: center;
    }

    .right {
        text-align: right;
    }

    .clear {
        clear: both;
    }

    .terms-header {
        margin-top: 0px;
    }

    p {
        margin: 0px 10px;
        text-align: justify;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    td,
    th {
        padding: 10px;
        border: 1px solid #000;
    }

    .checkbox-container {
        padding-left: 6px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .checked-box {
        width: 20px;
        height: 20px;
    }

    .page-break {
        page-break-before: always;
    }

    .footer-table table {
        width: 100%;
        border-collapse: collapse;
    }

    .footer-table td,
    th {
        border: 0px;
    }


    @page {
        margin: 40px 20px 25px 20px;
        padding-left: 10px;
        padding-right: 10px;
    }

    @media print {

        /* Style for the print-only footer */
        .print-footer {
            page-break-before: auto;
            text-align: left;
            font-size: 12px;
            padding-top: 10px;
            color: #0b0303;
            position: absolute;
            bottom: 0;
            width: 100%;
        }

        /* Ensure the footer only appears on the last page */
        .content::after {
            content: "";
            display: block;
            page-break-after: always;
        }

        .service-item-table {
            border: 1px solid #000;
            border-collapse: collapse;
        }

        .service-item-table th,
        .service-item-table td {
            border: 1px solid #000;
        }
    }
</style>
