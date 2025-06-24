const loaderHTMLstyle = `
    <div id="table-loader" style="
        display: none;       
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 10000;
        background: rgba(248, 251, 255, 0.96);
        backdrop-filter: blur(6px);
        -webkit-backdrop-filter: blur(6px);
        border-radius: 12px;
    ">
        <div style="
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            color: #1e40af;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Inter', sans-serif;
        ">
            <div style="
                width: 60px;
                height: 60px;
                margin: 0 auto 24px;
                position: relative;
            ">
                <div style="
                    width: 100%;
                    height: 100%;
                    border: 2px solid rgba(59, 130, 246, 0.2);
                    border-radius: 50%;
                    animation: hospitalPulse 2s ease-in-out infinite;
                    position: absolute;
                "></div>
                
                <div style="
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                    width: 32px;
                    height: 32px;
                ">
                    <div style="
                        position: absolute;
                        top: 0;
                        left: 50%;
                        transform: translateX(-50%);
                        width: 6px;
                        height: 100%;
                        background: linear-gradient(45deg, #3b82f6, #1d4ed8);
                        border-radius: 3px;
                        animation: crossGlow 1.5s ease-in-out infinite alternate;
                    "></div>
                    <div style="
                        position: absolute;
                        top: 50%;
                        left: 0;
                        transform: translateY(-50%);
                        width: 100%;
                        height: 6px;
                        background: linear-gradient(45deg, #3b82f6, #1d4ed8);
                        border-radius: 3px;
                        animation: crossGlow 1.5s ease-in-out infinite alternate;
                    "></div>
                </div>
                
                <div style="
                    width: 80%;
                    height: 80%;
                    position: absolute;
                    top: 10%;
                    left: 10%;
                    border: 2px solid transparent;
                    border-top: 2px solid #10b981;
                    border-right: 2px solid #10b981;
                    border-radius: 50%;
                    animation: medicalSpin 3s linear infinite;
                "></div>
            </div>
            
            <div style="
                font-size: 16px;
                font-weight: 600;
                color: #1e40af;
                margin-bottom: 8px;
                letter-spacing: 0.5px;
                animation: textPulse 2s ease-in-out infinite;
            ">Processing Medical Data...</div>
            
            <div style="
                font-size: 13px;
                color: #64748b;
                font-weight: 500;
                animation: textPulse 2s ease-in-out infinite 0.5s;
            ">📋 Retrieving records securely</div>
            
            <div style="
                margin-top: 16px;
                display: flex;
                justify-content: center;
                gap: 6px;
            ">
                <div style="
                    width: 8px;
                    height: 8px;
                    background: #3b82f6;
                    border-radius: 50%;
                    animation: dotBounce 1.4s ease-in-out infinite;
                "></div>
                <div style="
                    width: 8px;
                    height: 8px;
                    background: #10b981;
                    border-radius: 50%;
                    animation: dotBounce 1.4s ease-in-out infinite 0.2s;
                "></div>
                <div style="
                    width: 8px;
                    height: 8px;
                    background: #8b5cf6;
                    border-radius: 50%;
                    animation: dotBounce 1.4s ease-in-out infinite 0.4s;
                "></div>
            </div>
        </div>
    </div>
    
    <style>
        @keyframes hospitalPulse {
            0%, 100% { 
                transform: scale(1);
                opacity: 0.8;
            }
            50% { 
                transform: scale(1.15);
                opacity: 0.4;
            }
        }
        
        @keyframes crossGlow {
            0% { 
                box-shadow: 0 0 5px rgba(59, 130, 246, 0.3);
                filter: brightness(1);
            }
            100% { 
                box-shadow: 0 0 15px rgba(59, 130, 246, 0.6);
                filter: brightness(1.2);
            }
        }
        
        @keyframes medicalSpin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        @keyframes textPulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
        
        @keyframes dotBounce {
            0%, 80%, 100% {
                transform: scale(0.8);
                opacity: 0.5;
            }
            40% {
                transform: scale(1.2);
                opacity: 1;
            }
        }
        
        #table-loader {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Inter', sans-serif;
        }
        
        #table-loader:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at center, rgba(59, 130, 246, 0.05) 0%, transparent 70%);
            border-radius: 12px;
        }
    </style>
`;

const LoaderController = {
    isVisible: false,

    show(tableElement = null) {
        if (tableElement) {
            const parentElement = tableElement.parentElement || tableElement;
            const existingLoader = parentElement.querySelector('#table-loader');
            if (existingLoader) {
                existingLoader.remove();
            }

            parentElement.style.position = 'relative';
            parentElement.insertAdjacentHTML('beforeend', loaderHTMLstyle);

            const loader = parentElement.querySelector('#table-loader');
            if (loader) {
                this.isVisible = true;
                loader.style.display = 'block';

                requestAnimationFrame(() => {
                    loader.style.opacity = '0';

                    requestAnimationFrame(() => {
                        loader.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
                        loader.style.opacity = '1';
                    });
                });
            }
        } else {
            document.body.insertAdjacentHTML('beforeend', loaderHTMLstyle);
            const loader = document.getElementById('table-loader');
            if (loader) {
                this.isVisible = true;
                loader.style.position = 'fixed';
                loader.style.display = 'block';

                requestAnimationFrame(() => {
                    loader.style.opacity = '0';

                    requestAnimationFrame(() => {
                        loader.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
                        loader.style.opacity = '1';
                    });
                });
            }
        }
    },

    hide(tableElement = null) {
        let loader;
        if (tableElement) {
            const parentElement = tableElement.parentElement || tableElement;
            loader = parentElement.querySelector('#table-loader');
        } else {
            loader = document.getElementById('table-loader');
        }

        if (loader) {
            this.isVisible = false;

            loader.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
            loader.style.opacity = '0';

            setTimeout(() => {
                loader.remove();
            }, 300);
        }
    }
};

function initializeTable(preloadData, preloadTotal, API, Tableid, columnKeys, actionHtml, idKey = 'id', condition = null) {
    const $tableWrapper = $(Tableid).find('tbody').parent();
    if (!$(Tableid).find('#table-loader').length) {
        $tableWrapper.css('position', 'relative').append(loaderHTMLstyle);
    }
    $(Tableid).find('#table-loader').show();
    const table = $(Tableid).DataTable({
        serverSide: true,
        searching: true,
        ordering: true,
        paging: true,
        lengthMenu: [5, 10, 25, 50],
        pageLength: 10,
        data: preloadData ? preloadData.data : null,
        processing: true,
        dom: 'Blfrtip',
        buttons: [
            { extend: 'copyHtml5', text: '<i class="fa fa-files-o"></i>', titleAttr: 'Copy', className: 'btn btn-default btn-copy' },
            { extend: 'excelHtml5', text: '<i class="fa fa-file-excel-o"></i>', titleAttr: 'Excel', className: 'btn btn-default btn-excel' },
            { extend: 'csvHtml5', text: '<i class="fa fa-file-text-o"></i>', titleAttr: 'CSV', className: 'btn btn-default btn-csv' },
            { extend: 'pdfHtml5', text: '<i class="fa fa-file-pdf-o"></i>', titleAttr: 'PDF', className: 'btn btn-default btn-pdf' },
            { extend: 'print', text: '<i class="fa fa-print"></i>', titleAttr: 'Print', className: 'btn btn-default btn-print' }
        ],
        language: {
            emptyTable: "No data available in table",
            processing: ""
        },
        columns: columnKeys.map(() => ({ data: null })),
        columnDefs: [{
            orderable: false,
            targets: -1
        },
        ...columnKeys.map((key, index) => ({
            targets: index,
            render: function (data, type, row, meta) {
                if (key === 'sno') {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
                if (key === 'action') {
                    let renderedAction = actionHtml.replace(/key:id/g, row[idKey]);
                    if (condition && row[condition.key]) {
                        const dynamicPart = condition.condition_fn(row[condition.key], row);
                        renderedAction += dynamicPart;
                    }
                    return renderedAction;
                }
                if (condition && key === condition.key) {
                    row[condition.key] = condition.condition_fn(row[condition.key]);
                }
                let value = row[key];
                if (!value) return '-';
                if (isDate(value)) return formatDate(value);
                if (isDateTime(value)) return formatDateTime(value);
                if (isDateTimeISO(value)) return formatToDMY(value);
                if (isTime(value)) return formatTime(value);
                return value;
            }
        }))
        ],
        ajax: preloadData ?
            function (data, callback) {
                $(Tableid).find('#table-loader').show();
                const start = Date.now();
                fetchData(data, function (result) {
                    const elapsed = Date.now() - start;
                    const delay = 1000 - elapsed;
                    setTimeout(() => {
                        callback(result);
                        $(Tableid).find('#table-loader').hide();
                    }, delay > 0 ? delay : 0);
                }, API, columnKeys);
            } : {
                url: API,
                dataSrc: 'data',
                data: function (d) {
                    d.page = Math.floor(d.start / d.length) + 1;
                    d.limit = d.length;
                },
                dataFilter: function (data) {
                    return data;
                }
            },
        initComplete: function () {
            $(Tableid).find('#table-loader').hide();
        }
    });

    $(Tableid).on('xhr.dt', function () {
        const $loader = $('#table-loader');
        if ($loader.is(':visible')) {
            setTimeout(() => $loader.hide(), 1000);
        } else {
            $loader.hide();
        }
    });
}
function fetchData(data, callback, API, columnKeys) {
    const page = Math.floor(data.start / data.length) + 1;
    const url =
        `${API}?page=${page}&limit=${data.length}&search=${encodeURIComponent(data.search.value || '')}`;
    fetch(url)
        .then(res => res.json())
        .then(result => {
            callback({
                draw: data.draw,
                recordsTotal: result.recordsTotal || 0,
                recordsFiltered: result.recordsTotal || 0,
                data: result.data || []
            });
        })
        .catch(() => {
            callback({
                draw: data.draw,
                recordsTotal: 0,
                recordsFiltered: 0,
                data: []
            });
        });
}

function isDate(val) {
    return /^\d{4}-\d{2}-\d{2}$/.test(val);
}

function isDateTime(val) {
    return /^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/.test(val);
}
function isDateTimeISO(val) {
    return /^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}\.\d{3}Z$/.test(val);
}

function isTime(val) {
    return /^\d{2}:\d{2}:\d{2}$/.test(val);
}

function formatDate(dateStr) {
    return new Date(dateStr).toLocaleDateString();
}
function formatToDMY(isoDateStr) {
    const date = new Date(isoDateStr);
    if (isNaN(date)) return "";
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const year = date.getFullYear();
    return `${day}/${month}/${year}`;
}
function formatDateTime(dateTimeStr) {
    return new Date(dateTimeStr).toLocaleString();
}

function formatTime(timeStr) {
    const [hour, minute, second] = timeStr.split(':').map(Number);
    const ampm = hour >= 12 ? 'PM' : 'AM';
    const hour12 = hour % 12 || 12;
    return `${hour12.toString().padStart(2, '0')}:${minute.toString().padStart(2, '0')} ${ampm}`;
}


//report toppayment method

function updatePaymentSummary(summary) {
    const tags = `
                <span class="tag">Total: ${formatRupees(summary.total || 0)}</span>
                <span class="tag">Cash: ${formatRupees(summary.cash || 0)}</span>
                <span class="tag">Online: ${formatRupees(summary.online || 0)}</span>
                <span class="tag">UPI: ${formatRupees(summary.upi || 0)}</span>
                <span class="tag">Card: ${formatRupees(summary.card || 0)}</span>
                <span class="tag">NEFT: ${formatRupees(summary.neft || 0)}</span>
            `;
    $('#payment-summary-tags').html(tags);
    $("#payment-summary-tags").show();
}

function formatRupees(amount) {
    return '₹' + parseFloat(amount).toFixed(2);
}


//ajax system
function sendAjaxRequest(url, method, formData, successCallback = null) {
    try {
        showPageLoader();
        $.ajax({
            url: url,
            type: method,
            data: JSON.stringify(formData),
            contentType: "application/json",
            dataType: "json",
            headers: {
                'Authorization': accesstoken
            },
            success: function (response) {
                if (response.status_code == "403") {
                    $.ajax({
                        type: "GET",
                        url: baseurl + 'site/refreshToken',
                        dataType: "json",
                        success: function (res) {
                            if (res?.newtoken) {
                                accesstoken = res.newtoken;
                                sendAjaxRequest(url, method, formData, successCallback);
                            } else {
                                errorMsg("Token refresh failed.");
                                hidePageLoader();
                            }
                        },
                        error: function () {
                            errorMsg("Unable to refresh token.");
                            hidePageLoader();
                        }
                    });
                    return;
                }
                try {
                    if (typeof successCallback === "function") {
                        successCallback(response);
                    }
                } catch (e) {
                    console.error("SuccessCallback Error:", e);
                    errorMsg("Unexpected error during callback.");
                    hidePageLoader();
                }
            },
            error: function (xhr) {
                try {
                    let msg = xhr.responseJSON?.message || "An error occurred";
                    errorMsg(msg);
                    hidePageLoader();
                } catch (e) {
                    console.error("Error handler failure:", e);
                    errorMsg("Unexpected error occurred.");
                    hidePageLoader();
                }
            },
            complete: function () {
                try {
                    hidePageLoader();
                } catch (e) {
                    console.error("Loader fade error:", e);
                }
            }
        });
    } catch (error) {
        console.error("sendAjaxRequest Error:", error);
        errorMsg("Request setup failed.");
        hidePageLoader();
    }
}


function razorpaystartProcess(jsonData, APICALLBACK, Methodforcallback) {
    try {
        if (jsonData["payment_mode"] === "Online") {
            handlePayment(jsonData).then((paymentSuccess) => {
                try {
                    if (paymentSuccess) {
                        jsonData["payment_gateway"] = "razorpay";
                        jsonData["payment_id"] = paymentSuccess.payment_id;
                        jsonData["payment_reference_number"] = paymentSuccess.reference_id;
                        sendAjaxRequest(APICALLBACK, Methodforcallback, jsonData, function (response) {
                            handleResponse(response);
                        });
                    } else {
                        errorMsg("Payment failed. Please try again.");
                        hidePageLoader();
                    }
                } catch (e) {
                    console.error("razorpaystartProcess.then Error:", e);
                    errorMsg("Payment processing failed.");
                    hidePageLoader();
                }
            }).catch((err) => {
                console.error("razorpaystartProcess.catch Error:", err);
                errorMsg("Unable to initiate payment.");
                hidePageLoader();
            });
        } else {
            sendAjaxRequest(APICALLBACK, Methodforcallback, jsonData, function (response) {
                handleResponse(response);
            });
        }
    } catch (error) {
        console.error("razorpaystartProcess Error:", error);
        errorMsg("Payment setup failed.");
        hidePageLoader();
    }
}

function handleResponse(response, statusofprint = false, printfunctionname = 'printAppointment', reload = true) {
    var openModal = document.querySelector('.modal.in');
    try {
        let mainObj = Array.isArray(response) ? response[0] : response;
        let dataKey = Object.keys(mainObj).find(k => k.trim() === "data");
        let payload = dataKey ? mainObj[dataKey] : mainObj;
        let isSuccess = payload?.status.toLowerCase() == "success";
        if (isSuccess) {
            let message = payload?.message || payload?.messege || "Operation successful";
            successMsg(message);
            const insertedId =
                payload?.inserted_details?.[0]?.id ||
                payload?.["inserted_details "]?.[0]?.id ||
                payload?.IPD_values?.[0]?.id;
            if (statusofprint && insertedId) {
                $(openModal).modal('hide');
                hidePageLoader();
                if (typeof window[printfunctionname] === 'function') {
                    window[printfunctionname](insertedId);
                }
                $('#ajaxlist').DataTable().ajax.reload(null, false);
            } else {
                $('.modal.in').last().modal('hide');
                hidePageLoader();
                if (reload) {
                    location.reload();
                }
            }
        } else {
            let message = payload?.message || payload?.messege || "Something went wrong";
            errorMsg(message);
            hidePageLoader();
        }
    } catch (error) {
        console.error("handleResponse Error:", error, response);
        errorMsg("Unexpected response format. Please try again.");
        hidePageLoader();
    } finally {
        try { $('#paymentTypeFormbtn').button('reset'); } catch (e) { }
        try { $('#submitBtn').button('reset'); } catch (e) { }
        hidePageLoader();
    }
}


function uploadFile(fileInput, callback) {
    if (!fileInput) {
        callback("");
        return;
    }
    if (fileInput.size > 10 * 1024 * 1024) {
        errorMsg("File size must be 10MB or smaller.");
        return;
    }
    var fileUploadData = new FormData();
    fileUploadData.append('file', fileInput);

    $.ajax({
        url: 'https://phr-api.plenome.com/file_upload',
        type: 'POST',
        data: fileUploadData,
        headers: {
            'Authorization': accesstoken
        },
        contentType: false,
        processData: false,
        success: function (response) {
            callback(response.data);
        },
        error: function () {
            errorMsg("File upload failed.");
        }
    });
}

function fetchBase64file(apiUrl, payload) {
    fetch(apiUrl, {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        headers: {
            'Authorization': accesstoken
        },
        body: JSON.stringify(payload)
    })
        .then(response => {
            if (!response.ok) throw new Error("Failed to fetch the document.");
            return response.text();
        })
        .then(base64File => {
            base64File = base64File.trim();
            const fileBlob = new Blob([Uint8Array.from(atob(base64File), c => c.charCodeAt(0))], {
                type: "application/pdf"
            });
            const fileURL = URL.createObjectURL(fileBlob);
            window.open(fileURL, "_blank");
        })
        .catch(() => {
            errorMsg("Failed to fetch the document. Please try again.");
        });
}
function handleDownload(file) {
    $.ajax({
        url: 'https://phr-api.plenome.com/file_upload/getDocs',
        type: "POST",
        contentType: "application/json",
        data: JSON.stringify({
            value: file
        }),
        success: function (res) {
            const cleanedFileName = file.replace(/_\d+$/, '');
            const a = document.createElement('a');
            a.href = "data:application/octet-stream;base64," + res;
            a.download = cleanedFileName;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        },
        error: function () {
            alert('Failed to download the document');
        }
    });
}

//reporttableajax
function initSummaryDataTable(baseurl, formSelector, tableSelector, summaryCallback, renderFunction = 'renderSummaryTable', filterType = '') {
    const $tableWrapper = $(tableSelector).find('tbody').parent();
    if (!$(tableSelector).find('#table-loader').length) {
        $tableWrapper.css('position', 'relative').append(loaderHTMLstyle);
    }
    $(tableSelector).find('#table-loader').show();
    return $(tableSelector).DataTable({
        processing: true,
        serverSide: true,
        searching: true,
        ordering: true,
        paging: true,
        lengthMenu: [5, 10, 25, 50],
        pageLength: 10,
        columnDefs: [{ orderable: false, targets: -1 }],
        dom: 'Blfrtip',
        buttons: [
            { extend: 'copyHtml5', text: '<i class="fa fa-files-o"></i>', titleAttr: 'Copy', className: 'btn btn-default btn-copy' },
            { extend: 'excelHtml5', text: '<i class="fa fa-file-excel-o"></i>', titleAttr: 'Excel', className: 'btn btn-default btn-excel' },
            { extend: 'csvHtml5', text: '<i class="fa fa-file-text-o"></i>', titleAttr: 'CSV', className: 'btn btn-default btn-csv' },
            { extend: 'pdfHtml5', text: '<i class="fa fa-file-pdf-o"></i>', titleAttr: 'PDF', className: 'btn btn-default btn-pdf' },
            { extend: 'print', text: '<i class="fa fa-print"></i>', titleAttr: 'Print', className: 'btn btn-default btn-print' }
        ],
        language: {
            emptyTable: "Select the Filter to get report",
            processing: ""
        },
        ajax: function (data, callback) {
            $(tableSelector).find('#table-loader').show();
            var formData = $(formSelector).serializeArray();
            const page = Math.floor(data.start / data.length) + 1;
            let API = '';
            if (baseurl === '') {
                API = `${filterType}&limit=${data.length}&search=${data.search.value}&page=${page}`;
            } else {
                API = `${baseurl}?limit=${data.length}&search=${data.search.value}&page=${page}&filter_type=${filterType}`;
                if (typeof currentFilter !== 'undefined') {
                    API += `&filter=${currentFilter}`;
                }
            }
            $.ajax({
                url: API,
                type: "POST",
                data: formData,
                dataType: "json",
                success: function (response) {
                    if (Array.isArray(response.payment_summary) && response.payment_summary.length > 0) {
                        if (response.payment_summary.every(payment => payment.hasOwnProperty("payment_mode"))) {
                            let paymentSummaryArray = response.payment_summary || [];
                            let formattedSummary = {
                                total: 0,
                                cash: 0,
                                online: 0,
                                upi: 0,
                                card: 0,
                                neft: 0
                            };
                            paymentSummaryArray.forEach(item => {
                                let mode = (item.payment_mode || '').toLowerCase();
                                let amount = parseFloat(item.totalAmount) || 0;
                                if (mode && formattedSummary.hasOwnProperty(mode)) {
                                    formattedSummary[mode] += amount;
                                }
                                formattedSummary.total += amount;
                            });
                            var paymentsummary = formattedSummary;
                        } else {
                            var paymentsummary = response.payment_summary;
                        }
                    } else {
                        var paymentsummary = response.payment_summary;
                    }

                    if (typeof window[renderFunction] === 'function') {
                        const renderedData = window[renderFunction](response.data, paymentsummary);
                        callback({
                            draw: data.draw,
                            recordsTotal: response.recordsTotal,
                            recordsFiltered: response.recordsFiltered,
                            data: renderedData
                        });
                    }
                    if (typeof summaryCallback === "function") summaryCallback(paymentsummary);
                    else {
                        $('#payment-summary-tags').hide();
                    }
                    $(tableSelector).find('#table-loader').hide();
                },
                error: function () {
                    $(tableSelector).find('#table-loader').hide();
                    callback({
                        draw: data.draw,
                        recordsTotal: 0,
                        recordsFiltered: 0,
                        data: []
                    });
                }
            });
        }
    });
}
function globalformatDate(dateStr) {
    const date = new Date(dateStr);
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const year = date.getFullYear();
    return `${day}/${month}/${year}`;
}


