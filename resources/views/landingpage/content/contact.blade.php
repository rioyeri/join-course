<style>
    .select2 {
        position: relative;
    }

    .select-selected {
        height: 60px;
    }
    .select2-container {
        height: 50px;
    }

    .select2-multiple {
        height: 50px;
    }

    .select2-container--default .select2-selection--single{
        border-radius: 1;
        height: 50px;
        border-color: #d8d8d8;
        border-radius: 1%;
        padding-top:10px;
        padding-left:5px;
        font-size: 15px;
    }

    .select2-container--default .select2-selection--multiple{
        border-radius: 1;
        height: 50px;
        border-color: #d8d8d8;
        border-radius: 1%;
        padding-top:10px;
        padding-left:10px;
        font-size: 15px;
        width: 100%;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color:gray;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__rendered {
        color:gray;
    }

    .select2-container--default .select2-selection--single[aria-expanded=true] {
        border-color: var(--color-primary);   
    }

    .select2-container--default .select2-selection--multiple[aria-expanded=true] {
        border-color: var(--color-primary);
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        padding-top: 40px;
    }
</style>
<!-- ======= Contact Section ======= -->
<section id="contact" class="contact">
    <div class="container" data-aos="fade-up">

        <div class="row gx-lg-0 gy-4">

            {{-- <div class="col-lg-4">

                <div class="info-container d-flex flex-column align-items-center justify-content-center">
                    <div class="info-item d-flex">
                        <i class="bi bi-geo-alt flex-shrink-0"></i>
                        <div>
                            <h4>Location:</h4>
                            <p>A108 Adam Street, New York, NY 535022</p>
                        </div>
                    </div><!-- End Info Item -->

                    <div class="info-item d-flex">
                        <i class="bi bi-envelope flex-shrink-0"></i>
                        <div>
                            <h4>Email:</h4>
                            <p>info@example.com</p>
                        </div>
                    </div><!-- End Info Item -->

                    <div class="info-item d-flex">
                        <i class="bi bi-phone flex-shrink-0"></i>
                        <div>
                            <h4>Call:</h4>
                            <p>+1 5589 55488 55</p>
                        </div>
                    </div><!-- End Info Item -->

                    <div class="info-item d-flex">
                        <i class="bi bi-clock flex-shrink-0"></i>
                        <div>
                            <h4>Open Hours:</h4>
                            <p>Mon-Sat: 11AM - 23PM</p>
                        </div>
                    </div><!-- End Info Item -->
                </div>

            </div> --}}
            <div class="col-lg-3"></div>

            <div class="col-lg-6">        
                <form action="{{ route('neworder') }}" method="post" role="form" class="php-email-form">
                    @csrf
                    <div class="section-header" style="margin-bottom:-40px">
                        <h2>Mulai Kursus</h2>
                    </div>
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <input type="text" class="form-control" name="user_name" id="user_name" placeholder="Nama Lengkap Siswa" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 form-group mt-3 mt-md-0">
                            <input type="text" class="form-control" name="user_phone" id="user_phone" placeholder="Nomor Whatsapp" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <input type="text" class="form-control" name="user_school" id="user_school" placeholder="Nama Sekolah" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 form-group mt-3 mt-md-0">
                            <select class="form-control select2" parsley-trigger="change" name="grade_id" id="grade_id">
                                <option value="#" disabled selected>Kelas</option>
                                @foreach ($grades as $grade)
                                    <option value="{{$grade->id}}">{{$grade->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 form-group mt-3 mt-md-0">
                            <select class="form-control select2" parsley-trigger="change" name="course_id" id="course_id" onchange="get_teacher(this.value)">
                                <option value="#" disabled selected>Mata Pelajaran</option>
                                @foreach ($courses as $course)
                                    <option value="{{$course->id}}">{{$course->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 form-group mt-3 mt-md-0">
                            <select class="form-control" parsley-trigger="change" name="teacher_id" id="teacher_id" onchange="get_schedule(this.value)">
                                <option value="#" disabled selected>Guru</option>
                                @foreach ($teachers as $teacher)
                                    <option value="{{$teacher->id}}" data-text="{{ $teacher->isItInstantOrder() }}">{{$teacher->teacher->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 form-group mt-3 mt-md-0">
                            <select class="form-control select2" parsley-trigger="change" name="package_id" id="package_id">
                                <option value="#" disabled selected>Paket</option>
                                @foreach ($packages as $package)
                                    <option value="{{$package->id}}">{{$package->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div id="line_schedule" style="display:none;">
                        <div class="row">
                            <div class="col-md-12 form-group mt-3 mt-md-0">
                                <select class="form-control select2 select2-multiple" style="width: 100%" multiple="multiple" multiple parsley-trigger="change" name="teacher_schedules[]" id="teacher_schedules" data-placeholder="Pilih Jadwal belajarmu">
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="text-center" style="margin:50px 0 50px 0"><button type="submit">Order Sekarang</button></div>
                </form>
            </div><!-- End Contact Form -->

            <div class="col-lg-3"></div>

        </div>

    </div>
</section><!-- End Contact Section -->
