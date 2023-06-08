<div class="modal fade" id="ajaxModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenter" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                        <form id="formInput" name="formInput" action="">
                                @csrf
                                <input type="hidden" name="id" id="formId">
                                <input type="hidden" id="_method" name="_method" value="">
                                <div class="modal-header">
                                        <h3 class="modal-title"><label id="headForm"></label> {{ Helper::head($title) }}</h3>
                                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                        <div class="row g-1">
                                                <div class="col-md-6">
                                                        <label>Nama</label>
                                                        <input type="text" class="form-control" name="nama" id="nama" required />
                                                </div>
                                                <div class="col-md-6">
                                                        <label>Alamat</label>
                                                        <input type="text" class="form-control" name="alamat" id="alamat" required />
                                                </div>
                                        </div>
                                        <div class="row g-1">
                                                <div class="col-md-12">
                                                        <label>Kategori</label>
                                                        <select class="form-control select2" id="kategori" name="kategori" style="width:100%">
                                                                <option value="Wisata Alam">Wisata Alam</option>
                                                                <option value="Wisata Air">Wisata Air</option>
                                                                <option value="Wisata Adat & Budaya">Wisata Adat & Budaya</option>
                                                        </select>
                                                </div>
                                        </div>
                                        <div class="row g-2 mt-1">
                                                <div class="col-md-12">
                                                        <label>Deskripsi</label>
                                                        <textarea class="form-control" name="deskripsi" id="deskripsi" required></textarea>
                                                </div>
                                        </div>
                                        <div class="row g-2 mt-1">
                                                <div class="col-md-6">
                                                        <label>Harga Tiket</label>
                                                        <input type="number" class="form-control" name="harga" id="harga" required />
                                                </div>
                                                <div class="col-md-6">
                                                        <label>Fasilitas</label>
                                                        <input type="text" class="form-control" name="fasilitas" id="fasilitas" required />
                                                </div>
                                        </div>
                                        <div class="row g-2 mt-1">
                                                <div class="col-md-12">
                                                        <label>Gambar</label>
                                                        <input class="form-control" type="file" name="image" id="image">
                                                        <input type="hidden" name="image_old" id="image_old">
                                                </div>
                                        </div>
                                        <div class="row g-2 mt-1">
                                                <div class="col-md-6">
                                                        <label>Latitude</label>
                                                        <input type="text" class="form-control" name="lat" id="lat" required />
                                                </div>
                                                <div class="col-md-6">
                                                        <label>Longitude</label>
                                                        <input type="text" class="form-control" name="lng" id="lng" required />
                                                </div>
                                        </div>
                                        <div class="row g-2 mt-1">
                                                <div class="col-md-12">
                                                        <label>Map</label>
                                                        <div id="map" style="width: 100%; height: 400px;"></div>
                                                </div>
                                        </div>
                                </div>
                                <div class="modal-footer">
                                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                                        <button class="btn btn-primary" type="button" id="saveBtn" value="create">Save</button>
                                        <button class="btn btn-primary" type="button" id="updateBtn" value="update">Update</button>
                                </div>
                        </form>
                </div>
        </div>
</div>


@push('jsScriptAjax')
<script type="text/javascript">
        let lats, longs;

        //Tampilkan form input
        function createForm() {
                $('#formInput').trigger("reset");
                $("#headForm").empty();
                $("#headForm").append("Form Input");
                $('#saveBtn').show();
                $('#updateBtn').hide();
                $('#formId').val('');
                $('#ajaxModel').modal('show');
                $('#_method').val('POST');
        }

        //Tampilkan form edit
        function editForm(id) {
                let urlx = "{{ $title }}"
                $.ajax({
                        url: urlx + '/' + id + '/edit',
                        type: "GET",
                        dataType: "JSON",
                        success: function(data) {
                                $("#headForm").empty();
                                $("#headForm").append("Form Edit");
                                $('#formInput').trigger("reset");
                                $('#ajaxModel').modal('show');
                                $('#saveBtn').hide();
                                $('#updateBtn').show();
                                $('#formId').val(data.id);
                                $('#_method').val('PUT');
                                $('#nama').val(data.nama);
                                $('#alamat').val(data.alamat);
                                $('#deskripsi').val(data.deskripsi);
                                $('#harga').val(data.harga);
                                $('#fasilitas').val(data.fasilitas);
                                $('#image_old').val(data.gambar);
                                $('#lat').val(data.lat);
                                $('#lng').val(data.lng);
                                $('#kategori').val(data.kategori.nama).trigger('change');
                                lats = data.lat;
                                longs = data.lng;
                                jalan(lats, longs);
                        },
                        error: function() {
                                var setting = {
                                        type: 'danger',
                                        allow_dismiss: true,
                                        newest_on_top: false,
                                        mouse_over: false,
                                        showProgressbar: true,
                                        spacing: 10,
                                        timer: 1000,
                                        placement: {
                                                from: 'top',
                                                align: 'right'
                                        },
                                        offset: {
                                                x: 30,
                                                y: 30
                                        },
                                        delay: 1000,
                                        z_index: 10000,
                                        animate: {
                                                enter: 'animated bounceIn',
                                                exit: 'animated bounceOut'
                                        }
                                }
                                $.notify('Unable to display data!', setting);
                        }
                });
        }

        function jalan(lat, long) {
                const image =
                        "https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png";
                const beachMarker = new google.maps.Marker({
                        position: {
                                lat: lat,
                                lng: long
                        },
                        map,
                        icon: image,
                });

                const pos = {
                        lat: lat,
                        lng: long,
                };

                infoWindow.setPosition(pos);
                infoWindow.setContent("Location found.");
                infoWindow.open(map);
                map.setCenter(pos);
        }


        let map, infoWindow;


        function initMap() {
                const myLatLng = {
                        lat: -5.5831396,
                        lng: 120.4310914
                };
                map = new google.maps.Map(document.getElementById("map"), {
                        zoom: 20,
                        center: myLatLng,
                });
                infoWindow.open(map);
                // Configure the click listener.
                map.addListener("click", (mapsMouseEvent) => {
                        // Close the current InfoWindow.
                        infoWindow.close();
                        // Create a new InfoWindow.
                        infoWindow = new google.maps.InfoWindow({
                                position: mapsMouseEvent.latLng,
                        });
                        infoWindow.setContent(
                                JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2)
                        );
                        infoWindow.open(map);
                        $('#lat').val(mapsMouseEvent.latLng.toJSON().lat)
                        $('#lng').val(mapsMouseEvent.latLng.toJSON().lng)
                });
        }


        window.initMap = initMap;
</script>
<script type="text/javascript" src="https://maps.google.com/maps/api/js?key=AIzaSyDAvs1mDQYuAxWGbSf7T5eO93v5VedySXc&callback=initMap"></script>
@endpush