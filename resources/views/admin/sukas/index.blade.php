@extends('admin._layouts.index')

@push('cssScript')
@include('admin._layouts._css-table')
@endpush

@push($title)
active
@endpush

@section('content')
<div class="page-body">
        <div class="container-fluid">
                <div class="page-title">
                        <div class="row">
                                <div class="col-6">
                                        <h3>{{ Helper::head($title) }}</h3>
                                </div>
                                <div class="col-6">
                                        <ol class="breadcrumb">
                                                <li class="breadcrumb-item"><a href="index.html"> <i data-feather="home"></i></a></li>
                                                <li class="breadcrumb-item">{{ Helper::head($title) }}</li>
                                                <li class="breadcrumb-item active">Data</li>
                                        </ol>
                                </div>
                        </div>
                </div>
        </div>
        <!-- Container-fluid starts-->
        <div class="container-fluid">
                <div class="row project-cards">
                        <div class="col-md-12 project-list">
                                <div class="card">
                                        <div class="row">
                                                <div class="col-md-6 p-0 d-flex">
                                                        <ul class="nav nav-tabs border-tab" id="top-tab" role="tablist">
                                                                <li class="nav-item"><a class="nav-link active" id="top-home-tab" data-bs-toggle="tab" href="projects.html#top-home" role="tab" aria-controls="top-home" aria-selected="true"><i data-feather="target"></i>All</a></li>
                                                                <!-- <li class="nav-item"><a class="nav-link" id="profile-top-tab" data-bs-toggle="tab" href="projects.html#top-profile" role="tab" aria-controls="top-profile" aria-selected="false"><i data-feather="info"></i>Doing</a></li>
                                                                <li class="nav-item"><a class="nav-link" id="contact-top-tab" data-bs-toggle="tab" href="projects.html#top-contact" role="tab" aria-controls="top-contact" aria-selected="false"><i data-feather="check-circle"></i>Done</a></li> -->
                                                        </ul>
                                                </div>
                                                <div class="col-md-6 p-0">
                                                        <div class="form-group mb-0 me-0"></div>
                                                        {!! Helper::btn_create() !!}
                                                </div>
                                        </div>
                                </div>
                        </div>
                        <div class="product-wrapper">
                                <div class="feature-products">
                                        <div class="row">
                                                <div class="col-md-12">
                                                        <div class="pro-filter-sec">
                                                                <div class="product-sidebar">
                                                                        <div class="filter-section">
                                                                                <div class="card">
                                                                                        <div class="card-header">
                                                                                                <h4 class="mb-0 f-w-600">Filters<span class="pull-right"><i class="fa fa-chevron-down toggle-data"></i></span></h4>
                                                                                        </div>
                                                                                        <div class="left-filter">
                                                                                                <div class="card-body filter-cards-view animate-chk">
                                                                                                        <div class="product-filter">
                                                                                                                <h6 class="f-w-600">Active</h6>
                                                                                                                <select class="form-select" name="filter_active" id="filter_active">
                                                                                                                        <option value="1">Active</option>
                                                                                                                        <option value="0">Inactive</option>
                                                                                                                </select>
                                                                                                        </div>
                                                                                                </div>
                                                                                        </div>
                                                                                </div>
                                                                        </div>
                                                                </div>
                                                                @include('admin._card.search')
                                                        </div>
                                                </div>
                                        </div>
                                </div>
                        </div>
                        <!-- State saving Starts-->
                        <div class="col-sm-12">
                                <div class="card">
                                        <div class="card-header pb-0 row align-content-between">
                                                <div class="col-10">
                                                        <h3>Data</h3><span></span>
                                                </div>
                                                <div class="col-2">
                                                        <select class="form-select" name="jumlah" id="jumlah">
                                                                <option selected="selected">5</option>
                                                                <option>10</option>
                                                                <option>15</option>
                                                                <option>25</option>
                                                                <option>50</option>
                                                                <option>100</option>
                                                        </select>
                                                </div>
                                        </div>
                                        <div class="card-body">
                                                <div class="table-responsive">
                                                        <table class="table table-bordernone">
                                                                <thead>
                                                                        <tr>
                                                                                <th>No</th>
                                                                                <th>User</th>
                                                                                <th>Wisata</th>
                                                                                <th>Bintang</th>
                                                                        </tr>
                                                                </thead>
                                                                <tbody class="datatabels">
                                                                </tbody>
                                                                <tfoot>
                                                                        <tr>
                                                                                <th>No</th>
                                                                                <th>User</th>
                                                                                <th>Wisata</th>
                                                                                <th>Bintang</th>
                                                                        </tr>
                                                                </tfoot>
                                                        </table>
                                                </div>
                                                <div class="d-flex justify-content-between flex-wrap mt-4">
                                                        <div class="text-center">
                                                                <div id="contentx"></div>
                                                        </div>
                                                        <div class="text-center">
                                                                <ul class="pagination twbs-pagination pagination-primary">
                                                                </ul>
                                                        </div>
                                                </div>
                                        </div>
                                </div>
                        </div>
                        <!-- State saving Ends-->
                </div>
        </div>
        <!-- Container-fluid Ends-->
</div>

@endsection

@push('jsScript')
@include('admin._layouts._js-table')

@include('admin._card.crudJs')
@endpush