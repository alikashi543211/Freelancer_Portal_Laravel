<!-- jQuery -->
<script src="{{ url('plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ url('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- overlayScrollbars -->
<script src="{{ url('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ url('dist/js/adminlte.js') }}"></script>

<script>
    var messageView = false;
    $(document).ready(function () {
        getUnreadCount();
        setInterval(() => {
            getUnreadCount();
            checkBidsStatus();
        }, 20000);
    });

    function getUnreadCount() {
        $.ajax({
            url: "{{ url('messages/unread-messages') }}",
            method: "GET",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                messageView: messageView
            },
            success: function (res) {
                console.log(res);
                if (res.success) {
                    if (!messageView && res.count > 0) {
                        $('#message-count').html(res.count);
                        $('#message-count').show();
                    }
                    if (messageView) {
                        $('#threads').html(res.threads);
                    }

                }

            }

        });
    }

    function checkBidsStatus() {
        $.ajax({
            url: "{{ url('leads/bid-statuses') }}",
            method: "GET",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (res) {
                $('nav>ul.navbar-nav.ml-auto').html(res.html);
            }

        });
    }

</script>
