<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Test Administration</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../../../../asset/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="icon" href="{{ asset('assets/images/pup-logo.png') }}" type="image/x-icon">
    <link href="../../../../asset/vendor/fonts/circular-std/style.css" rel="stylesheet">
    <link rel="stylesheet" href="../../../../asset/libs/css/style.css">
    <link rel="stylesheet" href="../../../../asset/vendor/fonts/fontawesome/css/fontawesome-all.css">
    <link rel="stylesheet" type="text/css" href="../../../../asset/vendor/datatables/css/dataTables.bootstrap4.css">
    <link rel="stylesheet" type="text/css" href="../../../../asset/vendor/datatables/css/buttons.bootstrap4.css">
    <link rel="stylesheet" type="text/css" href="../../../../asset/vendor/datatables/css/select.bootstrap4.css">
    <link rel="stylesheet" type="text/css" href="../../../../asset/vendor/datatables/css/fixedHeader.bootstrap4.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .body-modal {
            max-height: 540px;
            overflow-y: auto;
        }

        .view-modal {
            max-height: 700px;
            overflow-y: auto;
        }

        .bordered-file-input {
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            padding: 0.375rem 0.75rem;
            background-color: #fff;
            box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
        }

        .modal-dialog {
            max-width: 600px;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        p {
            color: #3d405c;
        }

        strong {
            color: rgb(27, 27, 27);
        }

        .file-input-container {
            display: flex;
            flex-direction: column;
        }

        .file-input-container input[type="file"] {
            margin-bottom: 5px;
        }

        .file-input-container small {
            order: 1;
        }
        .table td {
        color: #3c3d43;
    }
    </style>
</head>

<body>
    @include('partials.faculty-sidebar')
    <div id="loading-spinner" class="loading-spinner">
        <div class="spinner"></div>
    </div>
    <div class="dashboard-wrapper">
        <div class="dashboard-ecommerce">
            <div class="container-fluid dashboard-content ">
                <!-- ============================================================== -->
                <!-- pageheader  -->
                <!-- ============================================================== -->
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="page-header">
                            <h2 class="pageheader-title">Test Administration</h2>
                            <div class="page-breadcrumb">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="#!"
                                                class="breadcrumb-link">Accomplishment</a></li>
                                        <li class="breadcrumb-item"><a href="" class="breadcrumb-link"
                                                style="cursor: default; color: #3d405c;">
                                                Test Administration </a></li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ============================================================== -->
                <!-- end pageheader  -->
                <!-- ============================================================== -->
                <div class="ecommerce-widget">
                    @if (auth()->user()->role == 'faculty-coordinator')
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mb-4">
                            <div class="simple-card">
                                <ul class="nav nav-tabs" id="myTab5" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active border-left-0" id="home-tab-simple" data-toggle="tab"
                                            href="#home-simple" role="tab" aria-controls="home"
                                            aria-selected="true">My Document Upload Progress</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="department-tab" data-toggle="tab" href="#department"
                                            role="tab" aria-controls="department" aria-selected="false">All
                                            Departments</a>
                                    </li>
                                </ul>

                                <div class="tab-content" id="myTabContent5">
                                    <div class="tab-pane fade show active" id="home-simple" role="tabpanel"
                                        aria-labelledby="home-tab-simple">
                                        <div class="card-body">
                                            <div class="alert alert-info d-flex align-items-center mb-4" role="alert">
                                                <i class="fas fa-exclamation-circle mr-2"></i>
                                                <p class="mb-0">This progress includes all documents that have been
                                                    approved by the admin.</p>
                                            </div>
                                            <h5 class="mb-3">Overall Progress</h5>
                                            <div class="progress mb-4">
                                                <div class="progress-bar" role="progressbar"
                                                    style="width: {{ $overallProgress }}%;"
                                                    aria-valuenow="{{ $overallProgress }}" aria-valuemin="0"
                                                    aria-valuemax="100">
                                                    {{ number_format($overallProgress, 2) }}%
                                                </div>
                                            </div>
                                            <hr>

                                            @php
                                                $currentMainFolder = null;
                                                $currentFolderId = request()->route('folder_name_id');
                                                $currentFolder = $folders->firstWhere(
                                                    'folder_name_id',
                                                    $currentFolderId,
                                                );
                                                if ($currentFolder) {
                                                    $currentMainFolder = $currentFolder->main_folder_name;
                                                }
                                            @endphp

                                            @if ($currentMainFolder && isset($folderProgress[$currentMainFolder]))
                                                <h5 class="mb-3">{{ $currentMainFolder }} Progress</h5>
                                                <div class="progress mb-4">
                                                    <div class="progress-bar" role="progressbar"
                                                        style="width: {{ $folderProgress[$currentMainFolder] }}%;"
                                                        aria-valuenow="{{ $folderProgress[$currentMainFolder] }}"
                                                        aria-valuemin="0" aria-valuemax="100">
                                                        {{ number_format($folderProgress[$currentMainFolder], 2) }}%
                                                    </div>
                                                </div>
                                                <hr>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="department" role="tabpanel" aria-labelledby="department-tab" style="padding: 20px;">
                                        <div class="alert alert-info d-flex align-items-center mb-4" role="alert">
                                            <i class="fas fa-info-circle mr-2"></i>
                                            <p class="mb-0">This progress shows the overall progress for each department.</p>
                                        </div>
                                        <div id="departmentList"></div>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    @elseif(auth()->user()->role == 'faculty')
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mb-4">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <div class="alert alert-info d-flex align-items-center" role="alert">
                                        <i class="fas fa-exclamation-circle me-2"></i>
                                        <p class="mb-0">This progress includes all documents that have been approved
                                            by the admin.</p>
                                    </div>
                                    <h6>Overall Progress</h6>
                                    <div class="progress mb-3">
                                        <div class="progress-bar" role="progressbar"
                                            style="width: {{ $overallProgress }}%;"
                                            aria-valuenow="{{ $overallProgress }}" aria-valuemin="0"
                                            aria-valuemax="100">
                                            {{ number_format($overallProgress, 2) }}%
                                        </div>
                                    </div>

                                    @php
                                        $currentMainFolder = null;
                                        $currentFolderId = request()->route('folder_name_id');
                                        $currentFolder = $folders->firstWhere('folder_name_id', $currentFolderId);
                                        if ($currentFolder) {
                                            $currentMainFolder = $currentFolder->main_folder_name;
                                        }
                                    @endphp

                                    @if ($currentMainFolder && isset($folderProgress[$currentMainFolder]))
                                        <h6>{{ $currentMainFolder }} Progress</h6>
                                        <div class="progress mb-3">
                                            <div class="progress-bar" role="progressbar"
                                                style="width: {{ $folderProgress[$currentMainFolder] }}%;"
                                                aria-valuenow="{{ $folderProgress[$currentMainFolder] }}"
                                                aria-valuemin="0" aria-valuemax="100">
                                                {{ number_format($folderProgress[$currentMainFolder], 2) }}%
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mb-4">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <div class="alert alert-warning" role="alert">
                                        You do not have permission to view this content.
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"> Test Administration (an academic document that communicates
                                    information about a specific course and
                                    explains the rules, responsibilities, and expectations associated with it.)</h5>
                            </div>
                            <div class="card-body">

                                <!-- Upload Files Button -->
                                @if ($isUploadOpen)
                                    <p style="color: #222222;">
                                        <strong>Opened:</strong> {{ $formattedStartDate }}<br>
                                        <strong>Due:</strong> {{ $formattedEndDate }}<br>
                                    </p>
                                   
                                        <a href="#" class="btn btn-primary mb-3" data-bs-toggle="modal"
                                            data-bs-target="#addFolderModal" >
                                            <i class="fas fa-plus"></i> Upload Files
                                        </a>
                                @else
                                    <p class="text-danger">
                                        {{ $statusMessage }}
                                        @if ($statusMessage !== 'No upload schedule set.')
                                            <br><br>
                                            <strong style="color: #222222;">Opened:</strong>
                                            <span style="color: #222222;">{{ $formattedStartDate }}</span><br>
                                            <strong style="color: #222222;">Due:</strong>
                                            <span style="color: #222222;">{{ $formattedEndDate }}</span><br>
                                        @endif
                                    </p>
                                @endif

                                @if (!$isUploadOpen)
                                    <button type="button" class="btn btn-warning mb-2" data-toggle="modal"
                                        data-target="#requestModal">
                                        Request Upload Access
                                    </button>
                                @endif

                                <!-- Modal for Request to Open -->
                                <div class="modal fade" id="requestModal" tabindex="-1" role="dialog"
                                    aria-labelledby="requestModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="requestModalLabel">Request Upload Access
                                                </h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="{{ route('request.upload.access') }}" method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="reason" class="form-label">Reason for
                                                            Request</label>
                                                        <textarea class="form-control" id="reason" name="reason" rows="6" required></textarea>
                                                    </div>
                                                    <input type="hidden" name="user_login_id"
                                                        value="{{ auth()->id() }}">
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary">Submit
                                                        Request</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                              @if (session('success'))
                                <div id="successAlert" class="alert alert-success alert-dismissible fade show text-center" role="alert">
                                    {{ session('success') }}
                                </div>
                                
                                <script>
                                    setTimeout(function() {
                                        var alert = document.getElementById('successAlert');
                                        if (alert) {
                                            alert.classList.remove('show');  
                                            alert.classList.add('fade');     
                                            
                                            setTimeout(function() {
                                                alert.remove(); 
                                            }, 150);  
                                        }
                                    }, 6000);
                                </script>
                            @endif

                                @if ($errors->any())
                                    <div class="alert alert-danger alert-dismissible fade show text-center"
                                        role="alert">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                 <div class="d-flex justify-content-between mb-3">
                            <!-- Filter by Date Range -->
                            <div class="col-md-3 mb-2 position-relative">
                                <div class="form-group">
                                      <input type="text" name="dates" id="archive-dates" class="form-control" placeholder="Select Archive" />
                                </div>
                            </div>
                        
                            <!-- Filter by Semester Dropdown -->
                            <div class="col-md-3 mb-2 position-relative">
                                <select id="semesterFilter" class="form-control">
                                    <option value="">Select Semester</option>
                                    @foreach($semesters as $semester)
                                        <option value="{{ $semester }}">
                                            @switch($semester)
                                                @case('First Sem')
                                                    First Semester
                                                    @break
                                                @case('Second Sem')
                                                    Second Semester
                                                    @break
                                                @default
                                                    {{ $semester }}
                                            @endswitch
                                        </option>
                                    @endforeach
                                </select>
                                <i class="fas fa-chevron-down position-absolute" style="right: 25px; top: 40%; transform: translateY(-50%); pointer-events: none;"></i>
                            </div>
                        
                            <!-- Filter by School Year Dropdown -->
                            <div class="col-md-3 mb-2 position-relative">
                                <select id="schoolYearFilter" class="form-control">
                                    <option value="">Select School Year</option>
                                    @foreach($schoolYears as $year)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endforeach
                                </select>
                                <i class="fas fa-chevron-down position-absolute" style="right: 25px; top: 40%; transform: translateY(-50%); pointer-events: none;"></i>
                            </div>
                        
                            <!-- Filter by Folder Dropdown -->
                            <div class="col-md-3 mb-2 position-relative">
                                <select name="folder_name" class="form-control">
                                    <option value="">Select Folder</option>
                                    @foreach($folders->where('main_folder_name', 'Classroom Management') as $folder)
                                        <option value="{{ $folder->folder_name_id }}">{{ $folder->folder_name }}</option>
                                    @endforeach
                                </select>
                               <i class="fas fa-chevron-down position-absolute" style="right: 25px; top: 40%; transform: translateY(-50%); pointer-events: none;"></i>
                            </div>

                                    <!-- Archive Buttons -->
                                    <!--<div class="d-flex align-items-center">-->
                                    <!--    @if ($consolidatedFiles->contains('status', 'Approved'))-->
                                    <!--        <form id="archive-all-form" action="{{ route('files.archiveAll') }}"-->
                                    <!--            method="POST" class="mr-3">-->
                                    <!--            @csrf-->
                                    <!--            <button type="submit" class="btn btn-danger btn-sm">Archive</button>-->
                                    <!--        </form>-->

                                    <!--        <form id="archive-date-range-form"-->
                                    <!--            action="{{ route('files.archiveByDateRange') }}" method="POST"-->
                                    <!--            class="d-flex align-items-center mr-3">-->
                                    <!--            @csrf-->
                                    <!--            <div class="input-group">-->
                                    <!--                <div class="input-group-prepend">-->
                                    <!--                    <span class="input-group-text">From:</span>-->
                                    <!--                </div>-->
                                    <!--                <input type="date" name="from_date" id="from_date"-->
                                    <!--                    class="form-control form-control-sm mr-2" required>-->
                                    <!--                <div class="input-group-prepend">-->
                                    <!--                    <span class="input-group-text">To:</span>-->
                                    <!--                </div>-->
                                    <!--                <input type="date" name="to_date" id="to_date"-->
                                    <!--                    class="form-control form-control-sm" required>-->
                                    <!--                <div class="input-group-append">-->
                                    <!--                    <button type="submit" class="btn btn-danger btn-sm">Archive-->
                                    <!--                        by Date</button>-->
                                    <!--                </div>-->
                                    <!--            </div>-->
                                    <!--        </form>-->
                                    <!--    @endif-->
                                    <!--</div>-->
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered first"  id="courseTable">
                                        <thead>
                                            <tr>
                                                <th>
                                                    @if ($consolidatedFiles->contains('status', 'Approved'))
                                                        <input type="checkbox" id="select-all">
                                                    @else
                                                        &nbsp;
                                                    @endif
                                                </th>
                                                <th>No.</th>
                                                <th>Date & Time</th>
                                                <th>Requirements</th>
                                                <th>School Year</th>
                                                <th>Semester</th>
                                                 <th>Subject</th>
                                                <th>Files</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                              @foreach ($consolidatedFiles as $file)
                                                <tr class="file-row" data-semester="{{ $file['semester'] }}" data-school-year="{{ $file['school_year'] }}">
                                                    <td>
                                                        @if ($file['status'] === 'Approved')
                                                            <input type="checkbox" class="file-checkbox" value="{{ $file['courses_files_id'] }}">
                                                        @else
                                                            &nbsp;
                                                        @endif
                                                    </td>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($file['files'][0]['created_at'])->timezone('Asia/Manila')->format('F j, Y, g:iA') }}</td>
                                                    <td>{{ $file['folder_name'] }}</td>
                                                    <td>{{ $file['semester'] }}</td>
                                                    <td> {{ $file['school_year'] }}</td>
                                                      <td> {{ $file['subject'] }}</td>
                                                    <td>
                                                        @foreach ($file['files'] as $fileInfo)
                                                            <div class="mb-1">
                                                                <a href="{{ Storage::url($fileInfo['path']) }}" target="_blank" style=" text-decoration: underline;  color: #3c3d43;">
                                                                    {{ $fileInfo['name'] }}
                                                                </a>
                                                                @if ($fileInfo['declined_reason'])
                                                                    <div class="small text-danger">Reason: {{ $fileInfo['declined_reason'] }}</div>
                                                                @endif
                                                            </div>
                                                        @endforeach
                                                    </td>
                                                    <td>
                                                      {{ $fileInfo['status'] }}
                                                    </td>
                                                  <td>
                                                    @if ($file['status'] !== 'Approved')
                                                        <button type="button" 
                                                                class="btn btn-warning btn-sm edit-files-btn" 
                                                                data-id="{{ $file['courses_files_id'] }}"
                                                                data-original-filename="{{ $file['files'][0]['name'] }}"
                                                                data-subject="{{ $file['subject'] }}">
                                                           </i> Edit
                                                        </button>
                                                    @endif
                                                </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit File Modal -->
         <div class="modal fade" id="editFileModal" tabindex="-1" role="dialog" aria-labelledby="editFileModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editFileModalLabel">Edit File</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="editFileForm" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" id="fileId" name="fileId">
                           <div class="form-group">
                                <strong>File: </strong>
                                <span id="originalFileName" class="form-control-static">file.pdf</span>
                            </div>
                            <div class="form-group">
                                <strong>Subject: </strong>
                                <span id="subject" class="form-control-static">Math</span>
                            </div>

                            <div class="form-group">
                                <label for="newFile">Upload New File</label>
                                <input type="file" class="form-control" id="newFile" name="new_file" required>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="saveChanges">Save Changes</button>
                    </div>
                </div>
            </div>
        </div>
        </div>

           <!-- Upload Files Modal -->
            <div class="modal fade" id="addFolderModal" tabindex="-1" aria-labelledby="addFolderModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addFolderModalLabel">Upload Files</h5>
                        </div>
                        <div class="modal-body body-modal">
                            <div class="d-flex justify-content-center mb-4">
                                @if(!$semesters->isEmpty() || !$schoolYears->isEmpty())
                                <h5 class="m-0">
                                    <strong>Instructions:</strong>
                                    Upload the files related to your teaching courses. All input fields with the symbol (<span style="color: red;">*</span>) are required. Only <strong>PDF</strong> file is accepted. 
                                    Please make sure to submit all the requirements related to the subject.
                                </h5>
                                @endif
                            </div>
            
                            <form id="uploadForm" action="{{ route('files.store.test-administration') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="folder_name_id" value="">
                
                                <!-- Semester -->
                                @if(!$semesters->isEmpty() || !$schoolYears->isEmpty())
                                    <div class="row">
                                        <div class="col-12 mb-3">
                                            <strong>Academic Year:</strong> <span>{{ $semesters->implode(', ') }} {{ $schoolYears->implode(', ') }}</span>
                                        </div>
                                    </div>
                      
                                <div class="position-relative">
                                    <select class="form-control mb-2" name="test_administration_folder" id="test_administration_folder" required>
                                        <option value="">Select Requirements</option>
                                        @foreach($folders->where('main_folder_name', 'Test Administration') as $folder)
                                            <option value="{{ $folder->folder_name }}">{{ $folder->folder_name }}</option>
                                        @endforeach
                                    </select>
                                    <i class="fas fa-chevron-down position-absolute" style="right: 25px; top: 50%; transform: translateY(-50%); pointer-events: none;"></i>
                                </div>

                             @endif
                                @if ($courseSchedules->isEmpty())
                                    <div class="text-center">
                                        <h4 class="mb-4 mt-3">No schedules available.</h4>
                                    </div>
                                @else
                                    @php
                                        $uniqueSubjects = $courseSchedules->unique(function ($item) {
                                            return $item->course_subjects . $item->course_code;
                                        });
                                    @endphp
            
                                    @foreach ($uniqueSubjects as $schedule)
                                        <div class="card mb-3">
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label for="file{{ $schedule->course_code }}" style="display: inline-block; margin-bottom: 0;">
                                                        <strong>Subject:</strong> {{ $schedule->course_subjects }}<br>
                                                        <strong>Subject Code:</strong> {{ $schedule->course_code }}<br>
                                                    </label>
                                                    <input type="file" class="form-control mb-2 mt-2 w-100"
                                                           id="fileInput{{ $schedule->course_code }}"
                                                           name="files[{{ $schedule->course_subjects }}][]" 
                                                           multiple
                                                           accept=".pdf"
                                                           required>
                                                    <div id="preview{{ $schedule->course_code }}" class="preview-container"></div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                
                                <div class="progress mt-3 d-none" id="uploadProgress">
                                    <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                                </div>
                
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    @if(!$courseSchedules->isEmpty())
                                        <button type="submit" class="btn btn-primary" id="uploadButton">Submit</button>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            </div>



        </div>
        <!-- ============================================================== -->
        <!-- end wrapper  -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- end main wrapper  -->
    <!-- ============================================================== -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../../../asset/vendor/jquery/jquery-3.3.1.min.js"></script>
    <script src="../../../../asset/vendor/bootstrap/js/bootstrap.bundle.js"></script>
    <script src="../../../../asset/vendor/slimscroll/jquery.slimscroll.js"></script>
    <script src="../../../../asset/vendor/multi-select/js/jquery.multi-select.js"></script>
    <script src="../../../../asset/libs/js/main-js.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="../../../../asset/vendor/datatables/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
    <script src="../../../../asset/vendor/datatables/js/buttons.bootstrap4.min.js"></script>
    <script src="../../../../asset/vendor/datatables/js/data-table.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.colVis.min.js"></script>
    <script src="https://cdn.datatables.net/rowgroup/1.0.4/js/dataTables.rowGroup.min.js"></script>
    <script src="https://cdn.datatables.net/select/1.2.7/js/dataTables.select.min.js"></script>
    <script src="https://cdn.datatables.net/fixedheader/3.1.5/js/dataTables.fixedHeader.min.js"></script>
    <script src="../../../../asset/vendor/datatables/js/loading.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/moment/min/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.js"></script>
 
    <script>
    //filter table
    document.addEventListener('DOMContentLoaded', function() {
        const semesterFilter = document.getElementById('semesterFilter');
        const schoolYearFilter = document.getElementById('schoolYearFilter');
        const folderFilter = document.querySelector('select[name="folder_name"]');
        const table = document.getElementById('courseTable');
        
        function createNoDataRow() {
            const tbody = table.getElementsByTagName('tbody')[0];
            const existingNoDataRow = tbody.querySelector('.no-data-row');
            
            if (!existingNoDataRow) {
                const noDataRow = document.createElement('tr');
                noDataRow.className = 'no-data-row';
                const noDataCell = document.createElement('td');
                noDataCell.colSpan = 10; 
                noDataCell.className = 'text-center py-4';
                noDataCell.innerHTML = '<div class="text-muted">No files found matching the selected filters</div>';
                noDataRow.appendChild(noDataCell);
                tbody.appendChild(noDataRow);
            }
        }
    
       function filterTable() {
        const selectedSemester = semesterFilter.value;
        const selectedSchoolYear = schoolYearFilter.value;
        const selectedFolder = folderFilter.options[folderFilter.selectedIndex].text;
        
        const tbody = table.getElementsByTagName('tbody')[0];
        const rows = tbody.getElementsByTagName('tr');
        let visibleRows = 0;
        
        const existingNoDataRow = tbody.querySelector('.no-data-row');
        if (existingNoDataRow) {
            existingNoDataRow.remove();
        }
        
        for (let row of rows) {
            if (row.className === 'no-data-row') continue;
            
            let showRow = true;
            
            const semester = row.cells[4].textContent.trim();
            const schoolYear = row.cells[5].textContent.trim();
            const folder = row.cells[3].textContent.trim();
            
            if (selectedSemester && semester !== selectedSemester) {
                showRow = false;
            }
            if (selectedSchoolYear && schoolYear !== selectedSchoolYear) {
                showRow = false;
            }
            if (selectedFolder !== 'Select Folder' && folder !== selectedFolder) {
                showRow = false;
            }
            
            row.style.display = showRow ? '' : 'none';
            if (showRow) {
                visibleRows++;
                row.cells[1].textContent = visibleRows; 
            }
        }
        
        if (visibleRows === 0) {
            createNoDataRow();
        }
        
    }
    
        semesterFilter.addEventListener('change', filterTable);
        schoolYearFilter.addEventListener('change', filterTable);
        folderFilter.addEventListener('change', filterTable);
    });
    
    //progress
     $(document).ready(function() {
        $('#uploadForm').on('submit', function(e) {
            e.preventDefault();
            
            $('#uploadProgress').removeClass('d-none');
            $('#uploadButton').text('Submitting...').prop('disabled', true);
            
            // Create FormData object
            var formData = new FormData(this);
            
            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                xhr: function() {
                    var xhr = new XMLHttpRequest();
                    
                    xhr.upload.addEventListener('progress', function(e) {
                        if (e.lengthComputable) {
                            var percent = Math.round((e.loaded / e.total) * 100);
                            $('#uploadProgress .progress-bar')
                                .css('width', percent + '%')
                                .attr('aria-valuenow', percent)
                                .text(percent + '%');
                        }
                    });
                    
                    return xhr;
                },
                success: function(response) {
                    $('#uploadProgress .progress-bar')
                        .css('width', '0%')
                        .attr('aria-valuenow', 0)
                        .text('0%');
                    $('#uploadProgress').addClass('d-none');
                    $('#uploadButton').text('Submit').prop('disabled', false);
                    
                    location.reload(); 
                },
                error: function(xhr, status, error) {
                    $('#uploadProgress .progress-bar')
                        .css('width', '0%')
                        .attr('aria-valuenow', 0)
                        .text('0%');
                    $('#uploadProgress').addClass('d-none');
                    $('#uploadButton').text('Submit').prop('disabled', false);
                    
                    alert('An error occurred during upload. Please try again.');
                }
            });
        });
    });
    
    //edit files
     $('.edit-files-btn').on('click', function() {
            const fileId = $(this).data('id');
            const originalFileName = $(this).data('original-filename');
            const subject = $(this).data('subject');
            
            $('#originalFileName').text(originalFileName);
            $('#subject').text(subject);
            $('#fileId').val(fileId);
            
            $('#editFileModal').modal('show');
        });
    
        $('#saveChanges').on('click', function() {
            const formData = new FormData($('#editFileForm')[0]);
            
            $.ajax({
                url: '/update-file',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        $('#editFileModal').modal('hide');
                        location.reload();
                    } else {
                        alert('Error updating file');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    alert('Error updating file');
                }
            });
       
    });
    
    //archive
    $(document).ready(function() {
        $('#archive-dates').daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear',
                applyLabel: 'Apply'
            }
        });
    
        $('#archive-dates').on('apply.daterangepicker', function(ev, picker) {
            const startDate = picker.startDate.format('MM/DD/YYYY');
            const endDate = picker.endDate.format('MM/DD/YYYY');
            
            console.log('Sending dates:', {startDate, endDate}); 
            
            Swal.fire({
                title: 'Are you sure?',
                text: `Do you want to archive all files created between ${startDate} and ${endDate}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, archive them!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('archive.by.test-administration') }}",
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            start_date: startDate,
                            end_date: endDate
                        },
                        success: function(response) {
                            console.log('Server response:', response); 
                            
                            if (response.success) {
                                if (response.count > 0) {
                                    Swal.fire(
                                        'Archived!',
                                        response.message,
                                        'success'
                                    ).then(() => {
                                        window.location.reload();
                                    });
                                } else {
                                    Swal.fire(
                                        'No Files Found',
                                        response.message,
                                        'info'
                                    );
                                }
                            } else {
                                Swal.fire(
                                    'Notice',
                                    response.message,
                                    'info'
                                );
                            }
                        },
                        error: function(xhr) {
                            console.error('Ajax error:', xhr); 
                            Swal.fire(
                                'Error',
                                'An error occurred while archiving files',
                                'error'
                            );
                        }
                    });
                }
            });
        });
    
        $('#archive-dates').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });
    });

    </script>
</body>

</html>
