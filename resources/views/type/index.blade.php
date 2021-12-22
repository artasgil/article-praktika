@extends('layouts.app')

@section('content')
<div class="container">

<div class="alerts">
</div>

<div class="alerts d-none">
</div>


<div class="search-form row">
    <div class="col-md-8">
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createTypeModal">
        Create New Type
    </button>


    <button class="btn btn-primary" id="delete-selected">Delete selected</button>
</div>
    <div class="col-md-4">
        Search: <input type="text" class="form-control" id="search-field" name="search-field"/>
        {{-- <button type="button" class="btn btn-primary" id="search-button" >Search</button> --}}
        <span class="search-feedback">
        </span>
        <div class="search-alert">
        </div>
    </div>
</div>





    <div class="sort-form row">
        <div class="col-md-4">
            <select class="form-control" id="sortCol" name="sortCol">
                <option value='id' selected="true">ID</option>
                <option value='title'>Title</option>
                <option value='description'>Description</option>
            </select>

            <select class="form-control" id="sortOrder" name="sortOrder">
                <option value='ASC' selected="true">ASC</option>
                <option value='DESC'>DESC</option>
            </select>

        <button type="button" id="filterTypes" class="btn btn-primary">Filter Types</button>
        </div>
        </div>
<table class="types table table-striped">
    <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Description</th>
        <th>Records</th>
        <th>Actions</th>
        <th><input type='checkbox' id='select_all_invoices' onclick="selectAll()"></th>
    </tr>

    @foreach ($types as $type)
        <tr class="type{{$type->id}}">
            <td class="colTypeId">{{$type->id}}</td>
            <td class="colTypeTitle">{{$type->title}}</td>
            <td class="colTypeDescription">{{$type->description}}</td>
            <td class="colTypeRecords">{{$type->articleTypes->count()}}</td>
            <td>
                <button type="button" class="btn btn-success show-type" data-typeid='{{$type->id}}'>Show</button>
                <button type="button" class="btn btn-secondary update-type" data-typeid='{{$type->id}}'>Update</button>
            </td>
            <td><input id="delete" class="delete-type check_invoice" type="checkbox"  name="typeDelete[]" value="{{$type->id}}" /></td>
        </tr>
    @endforeach
</table>
</div>
<div class="modal fade" id="createTypeModal" tabindex="-1" role="dialog" aria-labelledby="createTypeModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Create Type</h5>
          <button type="button" class="close" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="typetAjaxForm">
                <div class="form-group row">
                    <label for="typeTitle" class="col-md-4 col-form-label text-md-right">{{ __('Type ttile') }}</label>
                    <div class="col-md-6">
                        <input id="typeTitle" type="text" class="form-control" name="typeTitle">
                        <span class="invalid-feedback typeTitle" role="alert"></span>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="typeDescription" class="col-md-4 col-form-label text-md-right">{{ __('Description') }}</label>

                    <div class="col-md-6">
                        <textarea id="typeDescription" name="typeDescription" class="form-control">

                        </textarea>
                        <span class="invalid-feedback typeDescription" role="alert"></span>
                    </div>

                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary closeTypeModal" >Close</button>
          <button type="button" class="btn btn-primary addTypeModal">Add</button>
        </div>
      </div>
    </div>
</div>
    <div class="modal fade" id="showTypeModal" tabindex="-1" role="dialog" aria-labelledby="showTypeModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title show-typeTitle"></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <p class="show-typeDescription"></p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
    </div>

    <div class="modal fade" id="editTypeModal" tabindex="-1" role="dialog" aria-labelledby="editTypeModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Edit type</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <div class="typetAjaxForm">
                    <input type='hidden' id='edit-typeid'>
                    <div class="form-group row">
                        <label for="typeTitle" class="col-md-4 col-form-label text-md-right">{{ __('Type ttile') }}</label>
                        <div class="col-md-6">
                            <input id="edit-typeTitle" type="text" class="form-control" name="typeTitle">
                            <span class="invalid-feedback typeTitle" role="alert"></span>
                        </div>

                    </div>

                    <div class="form-group row">
                        <label for="typeDescription" class="col-md-4 col-form-label text-md-right">{{ __('Description') }}</label>

                        <div class="col-md-6">
                            <textarea id="edit-typeDescription" name="typeDescription" class="form-control">

                            </textarea>
                            <span class="invalid-feedback typeDescription" role="alert"></span>
                        </div>

                    </div>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary closeTypeModalUpdate"  >Close</button>
              <button type="button" class="btn btn-primary updateTypeModal">Update</button>
            </div>
          </div>
        </div>
    </div>


<script>
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
        }
    });
 $(document).ready(function() {
     $(".closeTypeModal, .close").click(function() {
        $("#createTypeModal").modal("hide");
        $("#typeTitle").val('');
        $("#typeDescription").val('');
        $(".invalid-feedback").css("display", 'none');
     });
    $(".addTypeModal").click(function() {
        var typeTitle = $("#typeTitle").val();
        var typeDescription = $("#typeDescription").val();
        $.ajax({
                type: 'POST',
                url: '{{route("type.storeAjax")}}',
                data: {typeTitle:typeTitle, typeDescription:typeDescription},
                success: function(data) {
                    if($.isEmptyObject(data.error)) {
                        $(".invalid-feedback").css("display", 'none');
                        $("#createTypeModal").modal("hide");
                        $(".types").append("<tr class=type" + data.typeid + " deleted><td>"+ data.typeid +"</td><td>"+ data.typeTitle +"</td><td>"+ data.typeDescription +"</td><td>{{$type->articleTypes->count()}}</td><td><button type='button' class='btn btn-success show-type' data-typeid='"+data.typeid+"'>Show</button><button type='button' class='btn btn-secondary update-type' data-typeid='"+data.typeid +"'>Update</button></td><td><input class='delete-type check_invoice' type='checkbox' name='typeDelete[]' value='"+data.typeid +"'/></td></tr>");
                        $(".alerts").append("<div class='alert alert-success'>"+ data.success +"</div");
        window.setTimeout(function() {
        $(".alert").fadeTo(500, 0).slideUp(1000, function(){
        $(this).remove();
        });
        }, 5000);
                        $("#typeTitle").val('');
                        $("#typeDescription").val('');
                    } else {
                        $(".invalid-feedback").css("display", 'none');
                        $.each(data.error, function(key, error){
                            var errorSpan = '.' + key;
                            $(errorSpan).css('display', 'block');
                            $(errorSpan).html('');
                            $(errorSpan).append('<strong>'+ error + "</strong>");
                        });
                    }
                }
            });
    });
 });

 $(document).on('click', '.show-type', function() {
       $('#showTypeModal').modal('show');
       var typeid = $(this).attr("data-typeid");
       $.ajax({
                type: 'GET',
                url: '/type/showAjax/' + typeid ,// action
                success: function(data) {
                    $('.show-typeTitle').html('');
                    $('.show-typeDescription').html('');
                    $('.show-typeTitle').append(data.typeid + '. ' + data.typeTitle);
                    $('.show-typeDescription').append(data.typeDescription);
                }
            });
    });


    $(document).on('click', '.update-type', function() {
        var typeid = $(this).attr('data-typeid');
        $("#editTypeModal").modal("show");
        $.ajax({
                type: 'GET',
                url: '/type/editAjax/' + typeid ,// action
                success: function(data) {
                  $("#edit-typeid").val(data.typeid);
                  $("#edit-typeTitle").val(data.typeTitle);
                  $("#edit-typeDescription").val(data.typeDescription);
                }
            });
    });

    $(".closeTypeModalUpdate, .close").click(function() {
        $(".invalid-feedback").css("display", 'none');
        $("#editTypeModal").modal("hide");
     });

    $(".updateTypeModal").click(function() {
        $(".invalid-feedback").css("display", 'none');
        var typeid = $("#edit-typeid").val();
        var typeTitle = $("#edit-typeTitle").val();
        var typeDescription = $("#edit-typeDescription").val();
        $.ajax({
                type: 'POST',
                url: '/type/updateAjax/' + typeid ,
                data: {typeTitle:typeTitle, typeDescription:typeDescription},
                success: function(data) {
                    if($.isEmptyObject(data.error)) {
                        $(".invalid-feedback").css("display", 'none');
                        $("#editTypeModal").modal("hide");
                        $(".alerts").append("<div class='alert alert-success'>"+ data.success +"</div>");
        window.setTimeout(function() {
        $(".alert").fadeTo(500, 0).slideUp(1000, function(){
        $(this).remove();
        });
        }, 5000);
                        $(".type"+ typeid + " .colTypeTitle").html(data.typeTitle);
                        $(".type"+ typeid + " .colTypeDescription").html(data.typeDescription);
                        // $(".type"+ typeid + " .colTypeRecords").html(data.typeRecord);
                    } else {
                        $(".invalid-feedback").css("display", 'none');
                        $.each(data.error, function(key, error){
                            var errorSpan = '.' + key;
                            $(errorSpan).css('display', 'block');
                            $(errorSpan).html('');
                            $(errorSpan).append('<strong>'+ error + "</strong>");
                        });
                    }
                }
            })
        });

        $(document).ready(function() {
            $("#delete-selected").click(function() {
                var checkedTypes = [];
                $.each( $(".delete-type:checked"), function( key, type) {
                    checkedTypes[key] = type.value;
                });

                $.ajax({
                type: 'POST',
                url: '{{route("type.destroySelected")}}',
                data: { checkedTypes: checkedTypes }, //JSON masyva
                success: function(data) {
                        $(".alerts").toggleClass("d-none");
                        for(var i=0; i<data.messages.length; i++) {
                            $(".alerts").append("<div class='alert alert-"+data.errorsuccess[i] + "'><p>"+ data.messages[i] + "</p></div>")
                            window.setTimeout(function() {
                            $(".alert").fadeTo(500, 0).slideUp(1000, function(){
                            $(this).remove();
                            });
                            }, 5000);
                            var id = data.success[i];
                            if(data.errorsuccess[i] == "success") {
                                $(".type"+id ).remove();
                            }
                        }
                    }
                });
            })

    });

    function createTable(types){
        $(".types tbody").html("");
        $(".types tbody").append("<tr><th>ID</th><th>Title</th><th>Description</th><th>Records</th><th>Actions</th><th><input type='checkbox' id='select_all_invoices' onclick='selectAll()'></th></tr>");
        $.each(types, function(key, type){
                var typeRow = "<tr class='type"+ type.id +"'>";
                typeRow += "<td class='colTypeId'>"+ type.id +"</td>";
                typeRow += "<td class='colTypeTitle'>"+ type.title +"</td>";
                typeRow += "<td class='colTypeDescription'>"+ type.description +"</td>";
                typeRow += "<td class='colTypeRecords'>"+ type.typeRecord+"</td>";
                typeRow += "<td>";
                typeRow += "<button type='button' class='btn btn-success show-type' data-typeid='"+ type.id +"'>Show</button>";
                typeRow += "<button type='button' class='btn btn-secondary update-type' data-typeid='"+ type.id +"'>Update</button>";
                typeRow += "</td>";
                typeRow += "<td>";
                typeRow += "<input id='delete' class='delete-type check_invoice' type='checkbox'  name='typeDelete[]'' value='"+ type.id + "'/>"
                typeRow += "</td>";
                typeRow += "</tr>";
                $(".types tbody").append(typeRow);
        });
    }

    var x = 0;
    $(document).on('input', '#search-field', function() {

        x++;
        var searchField = $("#search-field").val();
        var searchFieldCount = searchField.length;
        if(searchFieldCount != 0 && searchFieldCount < 3) {
            $(".search-feedback").css('display', 'block');
            $(".search-feedback").html("Min 3 symbols");
          //  x = 0;
        } else {
            $(".search-feedback").css('display', 'none');
        $.ajax({
                type: 'GET',
                url: '/type/searchAjax/',
                data: {searchField: searchField },
                success: function(data) {
                    if($.isEmptyObject(data.error)) {
                        console.log(data.success);
                        $(".types").css("display", 'table');
                        $(".search-alert").html("");
                        $(".search-alert").html(data.success);
                        createTable(data.types);
                        x=0;
                    } else {
                        $(".types").css("display", "none");
                        $(".types tbody").html("");
                        $(".search-alert").html("");
                        if(x> 0) {
                            window.alert(data.error);
                            // createTable(data.types);
                            x=0;
                        }
                        $(".search-alert").append(data.error);
                        $("#search-field").val('');
                        $(".types").css("display", "table");
                        createTable(data.types);


                        //if(data.error) {
                      //      $("#search-field").val('');
                      //      window.alert(data.error);
                      //      createTable(data.types);
                      //      $(".types").css("display", 'table');
                      //       }
                      //  $(".search-alert").append(data.error);
//
                        //console.log(data.error)
                   }
                }
            });
        }
    });

    $(document).on('click', '#filterTypes', function() {
        var sortCol = $("#sortCol").val();
        var sortOrder = $("#sortOrder").val();

        $.ajax({
                type: 'GET',
                url: '/type/filterAjax/',
                data: {sortCol: sortCol, sortOrder: sortOrder},
                success: function(data) {
                    if($.isEmptyObject(data.error)) {
                        // console.log(data)
                        createTable(data.types);
                        // console.log(data.type_id);
                    } else {
                        console.log(data.error)
                    }
                }

            });
    });

</script>
<script>
 function selectAll() {
        var blnChecked = document.getElementById("select_all_invoices").checked;
        var check_invoices = document.getElementsByClassName("check_invoice");
        var intLength = check_invoices.length;
        for(var i = 0; i < intLength; i++) {
            var check_invoice = check_invoices[i];
            check_invoice.checked = blnChecked;
        }
    }
</script>
@endsection
