@extends('layouts.app')

@section('submenu')
	@include('layouts.submenu.master')
@endsection

@section('content')
	<div class="container">
		<div class="row">
			<div class="col-md-12 col-sm-12">
				@include('vendor.flash.message')
				<form id="create-user-form" class="form-horizontal" method="post" action="{{ route('backend.master.user.store') }}">
					{!! csrf_field() !!}
					<div class="panel panel-default panel-box panel-create">
						<div class="panel-body">
							<div class="col-sm-12">
								<div class="panel-title-box">Tambah Data User</div>
							</div>
							<div class="col-sm-11 col-sm-offset-1">
								<div class="form-group" id="app">
									<label class="col-sm-2 control-label">NPK</label>
									<div class="col-sm-7">
										<autocomplete></autocomplete>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label">NPK Lama</label>
									<div class="col-sm-7">
										<input type="text" name="username" class="form-control" placeholder="NPK Lama (LDAP)" value="{{ old('username') }}" required>
										<span id="helpBlock" class="help-block">NPK Lama digunakan untuk login menggunakan LDAP</span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label">LDAP</label>
									<div class="col-sm-7">
										<div class="radio-inline">
											<input type="radio" name="ldap_active" value="0" checked> Tidak Aktif
										</div>
										<div class="radio-inline">
											<input type="radio" name="ldap_active" value="1"> Aktif
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label">Password</label>
									<div class="col-sm-7">
										<input type="password" name="password" class="form-control" placeholder="Password" required>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label">Konfirmasi Password</label>
									<div class="col-sm-7">
										<input type="password" name="password_confirmation" class="form-control" placeholder="Konfirmasi Password" required>
									</div>
								</div>
								<div class="form-group">
                                    <label class="col-sm-2 control-label">Hak Akses</label>
                                    <div class="col-sm-7">
                                        <ul class="container-check">
                                            @foreach($data['roles'] as $role)
                                                <li>
                                                    <label><input type="checkbox" name="IDRole[]" value="{{$role->ID}}"> {{$role->Role}}</label>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
							</div>
						</div>
					</div>
					<div class="text-right save-container">
						<button type="submit" id="save" class="btn btn-default btn-blue">Simpan</button>
						<a href="{{ route('backend.master.user') }}" type="button" class="btn btn-default btn-orange">Batal</a>
					</div>
				</form>
			</div>
		</div>
	</div>

	<script id="autocomplete-input-template" type="text/x-template">
	  	<div>
	        <input type="text" class="form-control" name="NPK" placeholder="NPK Karyawan" required 
	        	v-model="query" 
	        	v-on:keyup="autoComplete"
	        	@focus="isOpen = true"
	        	@keyup.esc="isOpen = false"
	        	@blur="isOpen = false"
	        	@input="onInput($event.target.value)">
	        <div class="autocomplete-data" v-show="isOpen">
	            <ul class="list-group">
	                <li class="list-group-item" v-for="result in results" :npk="result.NPK" @mousedown="selected(result.NPK, $event)">
	                    @{{ result.NamaKaryawan }} - @{{ result.NPK }}
	                </li>
	            </ul>
	        </div>
	    </div>
	</script>


@endsection

@section('customjs')
	<script src="https://unpkg.com/vue/dist/vue.js"></script>
	<script type="text/javascript">
		Vue.config.devtools = true;
	</script>

<script type="text/javascript">
  	Vue.component('autocomplete', {
		template: '#autocomplete-input-template',
	    data() {
	      	return {
	        	query: '',
	        	isOpen: false,
    			results: []
	      	}
	    },
	    methods: {
	      	autoComplete(){
		    	this.results = [];
		    	if(this.query.length > 0){
		     		$.getJSON('{{ url("/") }}/master/karyawan/search/npk?keyword='+this.query+'', function(response) {
                   		this.results = response.data;
                	}.bind(this));
		    	}
		    },
		    onInput(value) {
	          	this.isOpen = !!value
	        },
	        selected: function (data, event) {
				if (event){
					this.query = data
					$.getJSON('{{ url("/") }}/master/karyawan/search/npk?keyword='+data+'', function(response) {
                   		this.results = response.data;
                	}.bind(this));
				}
				this.isOpen = false
			}
		}
	})

  	new Vue({
		el: '#app'
 	})

 	$(function(){
	  	$('#save').one('click', function() {  
	    	$(this).attr('disabled','disabled');
	    	$('#create-user-form').submit();
	  	});
	});
</script>

@endsection