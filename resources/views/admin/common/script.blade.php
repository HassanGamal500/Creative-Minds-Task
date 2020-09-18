<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="{{asset('assetAdmin/plugins/jquery/jquery.min.js')}}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{asset('assetAdmin/plugins/jquery-ui/jquery-ui.min.js')}}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="{{asset('assetAdmin/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- Select2 -->
<script src="{{asset('assetAdmin/plugins/select2/js/select2.full.min.js')}}"></script>
<!-- daterangepicker -->
<script src="{{asset('assetAdmin/plugins/moment/moment.min.js')}}"></script>
<script src="{{asset('assetAdmin/plugins/daterangepicker/daterangepicker.js')}}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{asset('assetAdmin/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>
<!-- Summernote -->
<script src="{{asset('assetAdmin/plugins/summernote/summernote-bs4.min.js')}}"></script>
<!-- overlayScrollbars -->
<script src="{{asset('assetAdmin/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>
<!-- DataTables -->
<script src="{{asset('assetAdmin/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assetAdmin/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('assetAdmin/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('assetAdmin/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
<!-- jquery-validation -->
<script src="{{asset('assetAdmin/plugins/jquery-validation/jquery.validate.min.js')}}"></script>
<script src="{{asset('assetAdmin/plugins/jquery-validation/additional-methods.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('assetAdmin/dist/js/adminlte.js')}}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{asset('assetAdmin/dist/js/pages/dashboard.js')}}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{asset('assetAdmin/dist/js/demo.js')}}"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>


<script>
	
	$.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
        }
    });

	//Datatable
  	$(function () {
	    $("#example1").DataTable({
	      	"responsive": true,
	      	"autoWidth": false,
	    });
	    //Initialize Select2 Elements
    	$('.select2').select2()
	});

  	//Validation
	$(document).ready(function () {
  		$.validator.setDefaults({
		    // submitHandler: function () {
		    //   alert( "Form successful submitted!" );
		    // }
	  	});
	  	$('#quickForm').validate({
		    rules: {
		    	username: {  //  /^\W+$/
		    		required: true,
		    		maxlength: 50
		    	},
		    	phone: {
		    		required: true,
		    		minlength: 11,
		    		maxlength: 15
		    	},
		      	email: {
			        required: true,
			        email: true,
		      	},
		      	password: {
			        required: true,
			        minlength: 8
		      	}
		    },
		    messages: {
		    	username: {
		    		required: "Please enter Username",
		    		maxlength: "Your Username must be at most 50 characters long and contain letters, numbers, dashes and underscores"
		    	},
		    	phone: {
		    		required: "Please enter Your Phone Number",
		    		maxlength: "Your Phone must be at least 11 and most 15 Numbers"
		    	},
		      	email: {
		        	required: "Please Enter Your  Email Address",
		        	email: "Please Enter a Vaild Email Address"
		      	},
		      	password: {
		        	required: "Please provide a password",
		        	minlength: "Your password must be at least 8 characters long"
		      	}
		    },
		    errorElement: 'span',
		    errorPlacement: function (error, element) {
		      	error.addClass('invalid-feedback');
		      	element.closest('.form-group').append(error);
		    },
		    highlight: function (element, errorClass, validClass) {
		      	$(element).addClass('is-invalid');
		    },
		    unhighlight: function (element, errorClass, validClass) {
		      	$(element).removeClass('is-invalid');
		    }
	  	});

	  	$('#quickFormEdit').validate({
		    rules: {
		    	username: {
		    		required: true,
		    		maxlength: 50
		    	},
		    	phone: {
		    		required: true,
		    		minlength: 11,
		    		maxlength: 15
		    	},
		      	email: {
			        required: true,
			        email: true,
		      	},
		      	password: {
			        required: false,
			        minlength: 8
		      	},
		    },
		    messages: {
		    	username: {
		    		required: "Please enter Username",
		    		maxlength: "Your Username must be at most 50 characters long and contain letters, numbers, dashes and underscores"
		    	},
		    	phone: {
		    		required: "Please enter Your Phone Number",
		    		maxlength: "Your Phone must be at least 11 and most 15 Numbers"
		    	},
		      	email: {
		        	required: "Please Enter Your  Email Address",
		        	email: "Please Enter a Vaild Email Address"
		      	},
		      	password: {
		        	required: "Please provide a password",
		        	minlength: "Your password must be at least 8 characters long"
		      	},
		    },
		    errorElement: 'span',
		    errorPlacement: function (error, element) {
		      	error.addClass('invalid-feedback');
		      	element.closest('.form-group').append(error);
		    },
		    highlight: function (element, errorClass, validClass) {
		      	$(element).addClass('is-invalid');
		    },
		    unhighlight: function (element, errorClass, validClass) {
		      	$(element).removeClass('is-invalid');
		    }
	  	});
	});

	//Alert Delete
    $(document).on('click', '.alerts', function(e){
        var url = $(this).data("url");
        var id = $(this).data("id");
        var table = '.' + $(this).data('table');
        var thisClick = $(this).parents('tr');
        e.preventDefault();
        console.log(url);
        swal({
            title: "Are You Sure?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    type: 'DELETE',
                    url: url+id,
                    data: {id: id},
                    success:function(data){
                        var datatable = $(table).DataTable();
                        datatable.row(thisClick).remove().draw();
                        swal("Good job!", "Deleted Successfully!", "success");
                    }
                });
            } else {
                swal("ERROR!", "Delete Failed!", "error");
            }
        });
    });

</script>