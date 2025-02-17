<!-- Extra Large Modal -->
<div class="modal fade" id="import_model" tabindex="-1" role="dialog" aria-labelledby="import_model" aria-hidden="true"
     data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="import_form">
                <div class="block block-rounded shadow-none mb-0">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Import Data</h3>
                        <div class="block-options">
                            <button type="reset" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                                <i class="fa fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content fs-sm">
                        <div class="row mb-4">
                            <div class="col-12">
                                <label class="form-label" for="file">Select file</label>
                                <input type="file" class="form-control" id="file" name="file">
                            </div>
                        </div>
                    </div>
                    <div class="block-content block-content-full block-content-sm text-end border-top">
                        <div class="row">
                            <div class="col-6 text-start">
                                <a href="{{ url('templates/Import Transaction Template.xlsx') }}" class="btn btn-alt-primary" download>
                                    <i class="fa fa-download me-1"></i>
                                    Download Template
                                </a>
                            </div>
                            <div class="col-6">
                                <button type="reset" class="btn btn-alt-secondary import_close" data-bs-dismiss="modal">
                                    Close
                                </button>
                                <button type="submit" class="btn btn-alt-success">
                                    Import
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- END Extra Large Modal -->
