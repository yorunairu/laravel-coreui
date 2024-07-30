@extends('layouts.app')

@section('title', 'Dashboard - PT. Kencana Zavira')

@section('content')
<div class="container">
    <div class="row">
        <!-- Key Statistics Section -->
        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Demo Total Tenders</h5>
                    <p class="card-text">{{ $totalTenders }}</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Demo Pending Tenders</h5>
                    <p class="card-text">{{ $pendingTenders }}</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Demo Approved Tenders</h5>
                    <p class="card-text">{{ $approvedTenders }}</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Demo Rejected Tenders</h5>
                    <p class="card-text">{{ $rejectedTenders }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <!-- Recent Tenders Section -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Demo Recent Tenders</h5>
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Tender ID</th>
                                <th scope="col">Title</th>
                                <th scope="col">Status</th>
                                <th scope="col">Submission Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentTenders as $tender)
                            <tr>
                                <td>{{ $tender->id }}</td>
                                <td>{{ $tender->title }}</td>
                                <td>{{ $tender->status }}</td>
                                <td>{{ $tender->submission_date }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Quick Links Section -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Demo Quick Links</h5>
                    <ul class="list-group">
                        <li class="list-group-item"><a href="">Create New Tender</a></li>
                        <li class="list-group-item"><a href="">View All Tenders</a></li>
                        <li class="list-group-item"><a href="">Generate Reports</a></li>
                        <li class="list-group-item"><a href="">Settings</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
