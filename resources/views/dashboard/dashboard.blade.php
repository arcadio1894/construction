@extends('layouts.appAdmin2')

@section('title')
    Dashboard
@endsection

@section('page-header')
    <h1 class="page-title">Dashboard</h1>
@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.principal') }}"><i class="fa fa-home"></i> Dashboard</a>
        </li>
    </ol>
@endsection

@section('page-title')
    <h5 class="card-title">ACCESOS DIRECTOS</h5>
@endsection

@section('content')
    <div class="row">
        @can('list_customer')
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $customerCount }}</h3>

                    <p>Clientes</p>
                </div>
                <div class="icon">
                    <i class="ion ion-briefcase"></i>
                </div>
                <a href="{{ route('customer.index') }}" class="small-box-footer">Más detalles <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        @endcan
        @can('list_contactName')
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $contactNameCount }}</h3>

                    <p>Contactos</p>
                </div>
                <div class="icon">
                    <i class="ion ion-clipboard"></i>
                </div>
                <a href="{{ route('contactName.index') }}" class="small-box-footer">Más detalles <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        @endcan
        @can('list_supplier')
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $supplierCount }}</h3>

                    <p>Proveedores</p>
                </div>
                <div class="icon">
                    <i class="ion ion-ios-home-outline"></i>
                </div>
                <a href="{{ route('supplier.index') }}" class="small-box-footer">Más detalles <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        @endcan
        @can('list_material')
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $materialCount }}</h3>

                    <p>Materiales</p>
                </div>
                <div class="icon">
                    <i class="ion ion-ios-box"></i>
                </div>
                <a href="{{ route('material.index') }}" class="small-box-footer">Más detalles <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        @endcan
        @can('list_entryPurchase')
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>{{ $entriesCount }}</h3>

                    <p>Entradas a almacén</p>
                </div>
                <div class="icon">
                    <i class="ion ion-ios-cart"></i>
                </div>
                <a href="{{ route('entry.purchase.index') }}" class="small-box-footer">Más detalles <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        @endcan
        @can('list_invoice')
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-fuchsia">
                <div class="inner">
                    <h3>{{ $invoiceCount }}</h3>

                    <p>Facturas</p>
                </div>
                <div class="icon">
                    <i class="ion ion-card"></i>
                </div>
                <a href="{{ route('invoice.index') }}" class="small-box-footer">Más detalles <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        @endcan
        @can('list_request')
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-secondary">
                <div class="inner">
                    <h3>{{ $outputCount }}</h3>

                    <p>Salidas de almacén</p>
                </div>
                <div class="icon">
                    <i class="ion ion-android-exit"></i>
                </div>
                <a href="{{ route('output.request.index') }}" class="small-box-footer">Más detalles <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        @endcan
    </div>
@endsection
