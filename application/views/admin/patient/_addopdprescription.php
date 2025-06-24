<style>
label {
    display: inline-block;
    margin-bottom: 0.5rem;
    font-weight: 500;
    color: #495057;
}

.input-group {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
}

.input-group label {
    width: 120px;
    margin-bottom: 0;
    margin-right: 10px;
}

.input-group input {
    flex: 1;
    margin-right: 10px;
}

.input-group span {
    font-weight: 500;
    white-space: nowrap;
    color: #555;
    margin-left: 10px;
}

.card {
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    padding: 20px;
    margin-bottom: 20px;
}

h4 {
    margin-top: 25px;
    margin-bottom: 15px;
    font-weight: 600;
    color: #2c3e50;
    padding-bottom: 8px;
    border-bottom: 2px solid #e9ecef;
}

.card h4:first-child {
    margin-top: 0;
}

.vitals-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
    margin-bottom: 20px;
}

@media (max-width: 768px) {
    .vitals-container {
        grid-template-columns: 1fr;
    }

    .input-group {
        flex-direction: column;
        align-items: flex-start;
    }

    .input-group label {
        margin-bottom: 5px;
        width: 100%;
    }

    .input-group input {
        width: 100%;
        margin-right: 0;
    }

    .input-group span {
        margin-top: 5px;
        margin-left: 0;
    }
}

.tag {
    display: inline-flex;
    align-items: center;
    background: #28a745;
    color: white;
    padding: 5px 10px;
    margin: 5px;
    border-radius: 15px;
    font-size: 14px;
}

.tag .remove {
    margin-left: 8px;
    cursor: pointer;
    font-weight: bold;
}

.tag-container {
    display: flex;
    gap: 5px;
    margin-bottom: 10px;
}

.tag-container input {
    flex-grow: 1;
}

.complaint-tag {
    display: inline-flex;
    align-items: center;
    background-color: #f1f1f1;
    padding: 5px 10px;
    margin: 5px;
    border-radius: 5px;
}

.complaint-tag button {
    margin-left: 5px;
    background: red;
    color: white;
    border: none;
    cursor: pointer;
    padding: 2px 5px;
}
</style>
<style>
.suggestion-box {
    position: absolute;
    background: #fff;
    border: 1px solid #ddd;
    max-height: 200px;
    overflow-y: auto;
    width: 100%;
    display: none;
    z-index: 1000;
}

.suggestion-box div {
    padding: 8px;
    cursor: pointer;
}

.suggestion-box div:hover {
    background: #f0f0f0;
}
</style>
<?php
$api_base_url_casesheet = $this->config->item('api_base_url_casesheet');
?>
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <h4>Vitals</h4>
                <div class="vitals-container">
                    <div class="input-group">
                        <label>Temperature:</label>
                        <input type="number" name="temperature" class="form-control" placeholder="Enter temperature"
                            onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                            value="<?= $Vitals->temperature ?>" required>
                        <span>°F</span>
                    </div>
                    <div class="input-group">
                        <label>Pulse:</label>
                        <input type="number" name="pulse" class="form-control" placeholder="Enter pulse rate"
                            onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                            value="<?= $Vitals->pulse ?>" required>
                        <span>bpm</span>
                    </div>
                    <div class="input-group">
                        <label>Blood Pressure:</label>
                        <input type="text" name="bp" class="form-control" placeholder="Enter BP (e.g., 120/80)"
                            onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 47"
                            value="<?= $Vitals->bp ?>" required>
                        <span>mm Hg</span>
                    </div>
                    <div class="input-group">
                        <label>Weight:</label>
                        <input type="number" name="weight" class="form-control" placeholder="Enter weight"
                            onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                            value="<?= $Vitals->weight ?>" required>
                        <span>kg</span>
                    </div>
                    <div class="input-group">
                        <label>Height:</label>
                        <input type="number" name="height" class="form-control" placeholder="Enter height"
                            onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                            value="<?= $Vitals->height ?>" required>
                        <span>cm</span>
                    </div>
                    <div class="input-group">
                        <label>SpO2:</label>
                        <input type="number" name="spo2" class="form-control" placeholder="Enter SpO2 level"
                            onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                            value="<?= $Vitals->spo2 ?>" required>
                        <span>%</span>
                    </div>
                    <div class="input-group">
                        <label>Respiration:</label>
                        <input type="number" name="Respiration" class="form-control" placeholder="Enter Respiration level"
                            onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                            value="<?= $Vitals->respiration ?>" required>
                        <span>%</span>
                    </div>
                </div>
            </div>

            <div class="card">
                <h4>Chief Complaints</h4>
                <div class="tag-container" id="tagContainer">
                    <input type="text" id="complaintInput" class="form-control"
                        placeholder="Type complaint and click Add" onkeyup="validateComplaint()"
                        oninput="searchsugg(this.value)" name="complaint_name">
                    <button type="button" id="addComplaintBtn" onclick="addComplaint()" disabled>Add</button>
                    <div id="suggestions" class="suggestion-box"></div>
                </div>
                <div id="complaintsList"></div>
                <input type="hidden" name="chief_complaints" id="complaintsHidden">
            </div>

            <div class="card">
                <h4>Duration</h4>
                <div class="input-group">
                    <label for="durationInput">Duration:</label>
                    <input type="number" name="Chief_duration" id="durationInput" class="form-control"
                        placeholder="Enter number of days" maxlength="4" required>
                    <span>Days</span>
                </div>
            </div>

            <div class="card">
                <h4>Remarks</h4>
                <textarea name="remarks" class="form-control" required></textarea>
            </div>

            <div class="card">
                <h4>Past Treatment History</h4>
                <textarea name="past_treatment_history" class="form-control" required></textarea>
            </div>

            <div class="card">
                <h4>Treatment Advice</h4>
                <textarea name="history" class="form-control" required></textarea>
            </div>

            <div class="card">
                <h4>Diagnosis</h4>
                <table class="table table-striped table-bordered table-hover mb0" id="diagnosisTable">
                    <tbody>
                        <tr id="row1">
                            <td>
                                <input type="hidden" name="diagnosisrows[]" value="1">
                                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-6">
                                    <label>Test Categories</label>
                                    <input type="text" name="test_category_1" class="form-control" required>
                                </div>
                                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-6">
                                    <label>Sub Categories</label>
                                    <input type="text" name="sub_category_1" class="form-control" required>
                                </div>
                                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-6">
                                    <label>Laboratory</label>
                                    <input type="text" name="laboratory_1" class="form-control" required>
                                </div>
                                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-6">
                                    <label>Remarks</label>
                                    <input type="text" name="remarks_1" class="form-control" required>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <button type="button" class="btn btn-info" id="addDiagnosisRow">+ Add Row</button>
            </div>

            <div class="card">
                <h4>Diet Plan</h4>
                <input type="hidden" name="opd_id" value="<?=$visit_details_id?>">
                <textarea name="diet_plan" class="form-control" required></textarea>
            </div>

            <div class="card">
                <h4>Medications</h4>
                <table class="table table-striped table-bordered table-hover mb0" id="tableID">
                    <tr id="row1">
                        <td>
                            <input type="hidden" name="rows[]" value="1">
                            <div class="col-lg-2 col-md-4 col-sm-6 col-xs-6">
                                <label>Medicine Name</label>
                                <input type="text" class="form-control" name="medicine" required>
                            </div>
                            <div class="col-lg-2 col-md-4 col-sm-6 col-xs-6">
                                <label>Dosage</label>
                                <input type="text" class="form-control" name="dosage" required>
                            </div>
                            <div class="col-lg-2 col-md-4 col-sm-6 col-xs-6">
                                <label>Frequency</label>
                                <input type="text" class="form-control" name="frequency" required>
                            </div>
                            <div class="col-lg-2 col-md-4 col-sm-6 col-xs-6">
                                <label>Timing</label>
                                <input type="text" class="form-control" name="timing" required>
                            </div>
                            <div class="col-lg-2 col-md-4 col-sm-6 col-xs-6">
                                <label>Duration</label>
                                <input type="text" class="form-control" name="duration" required>
                            </div>
                            <div class="col-lg-2 col-md-4 col-sm-6 col-xs-6">
                                <label>Quantity</label>
                                <input type="text" class="form-control" name="quantity" required>
                            </div>
                            <div class="col-lg-2 col-md-4 col-sm-6 col-xs-6">
                                <label>Remarks</label>
                                <input type="text" class="form-control" name="remarks" required>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>


<script>
function validateComplaint() {
    const complaintInput = document.getElementById('complaintInput');
    const addComplaintBtn = document.getElementById('addComplaintBtn');
    if (complaintInput.value.trim() !== '') {
        addComplaintBtn.disabled = false;
    } else {
        addComplaintBtn.disabled = true;
    }
}

function addComplaint() {
    const complaintInput = document.getElementById('complaintInput');
    const complaintsList = document.getElementById('complaintsList');
    const complaintsHidden = document.getElementById('complaintsHidden');

    if (complaintInput.value.trim() !== '') {
        const complaint = document.createElement('div');
        complaint.className = 'complaint-tag';
        complaint.innerHTML = `
                <span>${complaintInput.value}</span>
                <button type="button" class="btn btn-danger btn-sm removeComplaint">X</button>
            `;
        complaintsList.appendChild(complaint);

        if (complaintsHidden.value === '') {
            complaintsHidden.value = complaintInput.value;
        } else {
            complaintsHidden.value += `, ${complaintInput.value}`;
        }

        complaintInput.value = '';
        validateComplaint();
    }
}

document.addEventListener('click', function(event) {
    if (event.target.classList.contains('removeComplaint')) {
        const complaintTag = event.target.closest('.complaint-tag');
        const complaintText = complaintTag.querySelector('span').innerText;
        const complaintsHidden = document.getElementById('complaintsHidden');

        complaintsHidden.value = complaintsHidden.value.replace(complaintText, '').replace(/, ,/g,
            ',').trim();

        if (complaintsHidden.value.startsWith(',')) {
            complaintsHidden.value = complaintsHidden.value.substring(1).trim();
        }
        if (complaintsHidden.value.endsWith(',')) {
            complaintsHidden.value = complaintsHidden.value.substring(0, complaintsHidden.value
                .length - 1).trim();
        }

        complaintTag.remove();
    }
});
document.addEventListener('click', function(event) {
    if (event.target.classList.contains('delete_row')) {
        event.target.closest('tr').remove();
    }
});
</script>
<script>
document.getElementById('addDiagnosisRow').addEventListener('click', function() {
    const table = document.getElementById('diagnosisTable').getElementsByTagName('tbody')[0];
    const rowCount = Date.now();

    const newRow = document.createElement('tr');
    newRow.innerHTML = `
                        <td>
                            <input type="hidden" name="diagnosisrows[]" value="${rowCount}">
                            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-6">
                                <div>
                                    <label>Test Categories</label>
                                    <input type="text" name="test_category_${rowCount}" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-6">
                                <div>
                                    <label>Sub Categories</label>
                                    <input type="text" name="sub_category_${rowCount}" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-6">
                                <div>
                                    <label>Laboratory</label>
                                    <input type="text" name="laboratory_${rowCount}" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-6">
                                <div>
                                    <label>Remarks</label>
                                    <input type="text" name="remarks_${rowCount}" class="form-control">
                                </div>
                            </div>
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger removeRow">X</button>
                        </td>
                    `;

    table.appendChild(newRow);
});
document.addEventListener('click', function(event) {
    if (event.target.classList.contains('removeRow')) {
        event.target.closest('tr')?.remove();
    }
});
</script>
<script>
function updateDate() {
    let count = document.getElementById("count").value;
    let dateField = document.getElementById("fllow_date");
    console.log(count);
    if (count && !isNaN(count)) {
        let today = new Date();
        today.setDate(today.getDate() + parseInt(count));

        let formattedDate = today.toISOString().split('T')[0];
        dateField.value = formattedDate;
    }
}
</script>
<script>
function searchsugg(value) {
    if (!value.trim()) {
        document.getElementById("suggestions").style.display = "none";
        return;
    }

    $.ajax({
        type: "GET",
        url: "<?=$api_base_url_casesheet?>chief-complaints",
        data: {
            search: value
        },
        dataType: "json",
        success: function(response) {
            let suggestionsBox = document.getElementById("suggestions");
            suggestionsBox.innerHTML = "";

            if (response.length > 0) {
                response.forEach(item => {
                    let div = document.createElement("div");
                    div.textContent = item.chief_complaints;
                    div.onclick = function() {
                        document.getElementById("complaintInput").value = item.chief_complaints;
                        suggestionsBox.style.display = "none";
                    };
                    suggestionsBox.appendChild(div);
                });

                suggestionsBox.style.display = "block";
            } else {
                suggestionsBox.style.display = "none";
            }
        }
    });
}

</script>