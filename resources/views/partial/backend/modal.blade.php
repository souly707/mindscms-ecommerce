<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
            <div class="modal-footer">
                <button class="btn btn-outline-secondary" type="button" data-dismiss="modal">Cancel</button>

                <a class="btn btn-outline-primary" href="javascript:void(0);"
                    onclick="event.preventDefault(); document.getElementById('loguot-form').submit()">
                    Logout
                </a>

                <form action="{{ route('logout') }}" method="POST" id="loguot-form" class="d-none">
                    @csrf
                </form>
            </div>
        </div>
    </div>
</div>