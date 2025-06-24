<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <?php $this->load->view('setting/sidebar.php'); ?>
            <div class="col-md-10">
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix">Department List</h3>
                    </div>
                      <div class="table-responsive mailbox-messages">
                        <table class="table table-striped table-bordered table-hover" id="ajaxlist">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($initialData)) : ?>
                                    <?php 
                                        $sno = 1;
                                        foreach ($initialData['data'] as $key=>$value) : ?>
                                        <tr>
                                            <td><?= $sno  ?></td>
                                            <td><?= $value['process_name'] ?></td>
                                            <td><?= $value['process_description'] ?></td>
                                            <td><?= date('d/m/Y', strtotime($value['created_at'])) ?></td>
                                            <td>
                                                <button class="btn btn-sm btn-info" onclick="viewQR(<?= $value['id']?>, '<?= addslashes($value['process_name']) ?>')">👀 View QR</button>
                                            </td>
                                        </tr>
                                    <?php $sno++;
                                        endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="5" class="text-center">No records found</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div id="qrModal" class="modales">
    <div class="modal-content" id="qr">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2 id="qrTitle"></h2>
        <div id="qrcode"></div><br/>
        <button class="btn btn-success" onclick="downloadCurrentQR()">Download PDF</button>
    </div>
</div>

<?php
$data = $this->session->userdata('hospitaladmin');
$api_base_url = $this->config->item('api_base_url');
?>

<script src="https://cdn.jsdelivr.net/npm/qrcodejs/qrcode.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
    const hospitalId = <?=$data["hospital_id"]?>;
    let currentQRData = null, currentProcessName = "";

    function generateStyledQRCode(data, containerId) {
        const container = document.getElementById(containerId);
        container.innerHTML = "";
        new QRCode(container, {
            text: JSON.stringify(data),
            width: 200,
            height: 200,
            colorDark: "#000",
            colorLight: "#fff",
            correctLevel: QRCode.CorrectLevel.H
        });
    }

    function viewQR(id, processName) {

         $.ajax({
                type: "POST",
                url: base_url + "schsettings/consultationprocess",
                data: {'id': id},
                dataType: "json",
                success: function (response) {
                    response.hospital_id = <?=$data["hospital_id"]?>;
                    currentQRData = response;
                    currentProcessName = processName;
                    $("#qrTitle").text(`${processName} QR Code`);
                    generateStyledQRCode(response, 'qrcode');
                    $('#qrModal').show();
                }
            });
    }

    function closeModal() {
        $('#qrModal').hide();
    }

    function downloadCurrentQR() {
        if (!currentQRData) return;
        const { jsPDF } = window.jspdf;
        const pdf = new jsPDF();
        pdf.setFontSize(20);
        pdf.text(`${currentProcessName} QR Code`, 50, 20);
        const canvas = document.querySelector('#qrcode canvas');
        if (canvas) {
            const imgData = canvas.toDataURL("image/png");
            pdf.addImage(imgData, 'PNG', 55, 30, 100, 100);
        }
        pdf.save(`${currentProcessName}_QR_Code.pdf`);
    }

    function downloadQR(id, processName) {
         $.ajax({
                type: "POST",
                url: base_url + "schsettings/consultationprocess",
                data: {'id': id},
                dataType: "json",
                success: function (response) {
                    response.hospital_id = <?=$data["hospital_id"]?>;
                    const { jsPDF } = window.jspdf;
                    const pdf = new jsPDF();
                    pdf.setFontSize(20);
                    pdf.text(`${processName} QR Code`, 50, 20);

                    const tempContainer = document.createElement('div');
                    tempContainer.style.position = "absolute";
                    tempContainer.style.visibility = "hidden";
                    document.body.appendChild(tempContainer);

                    const qrCanvas = document.createElement('canvas');
                    tempContainer.appendChild(qrCanvas);

                    new QRCode(qrCanvas, {
                        text: JSON.stringify(response),
                        width: 200,
                        height: 200,
                        colorDark: "#000",
                        colorLight: "#fff",
                        correctLevel: QRCode.CorrectLevel.H
                    });

                    setTimeout(() => {
                        const imgData = qrCanvas.toDataURL("image/png");
                        pdf.addImage(imgData, 'PNG', 55, 30, 100, 100);
                        pdf.save(`${processName}_QR_Code.pdf`);
                        document.body.removeChild(tempContainer);
                    }, 500);
            }
        });
    }
</script>

<style>
    .modales {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        overflow: auto;
        padding-top: 50px;
    }

    #qr {
        background-color: #fff;
        margin: 5% auto;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        width: 80%;
        max-width: 350px;
        text-align: center;
    }

    .close {
        color: #aaa;
        font-size: 30px;
        font-weight: bold;
        position: absolute;
        top: 10px;
        right: 20px;
        padding: 5px;
        cursor: pointer;
        transition: color 0.3s ease;
    }

    .close:hover,
    .close:focus {
        color: rgb(0, 255, 68);
    }

    #qrcode {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 15px;
    }
</style>
