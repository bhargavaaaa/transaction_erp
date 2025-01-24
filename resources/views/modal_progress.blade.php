<style>
    .svg-container {
        display: flex;
        justify-content: center; /* Center horizontally */
        align-items: center; /* Center vertically */
        height: 100%; /* Ensure the container takes up full height */
    }
</style>

<!-- Extra Large Modal -->
<div class="modal fade" id="order_progress_modal" tabindex="-1" role="dialog" aria-labelledby="order_progress_modal" aria-hidden="true"
     data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-sm">
            <form id="order_progress_form">
                <div class="block block-rounded shadow-none mb-0">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Order Progress</h3>
                        <div class="block-options">
                            <button type="reset" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                                <i class="fa fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content fs-sm">
                        <div id="embed_svg_code" class="svg-container">

                        </div>
                    </div>
                    <div class="block-content block-content-full block-content-sm text-end border-top">
                        <button type="reset" class="btn btn-alt-secondary order_progress_close" data-bs-dismiss="modal">
                            Close
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- END Extra Large Modal -->
