<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap Demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <select class="form-select" aria-label="Default select example" onchange="handleChangeProvince(this.value)">
                    <option value="">Select a Province</option>
                    @foreach ($dataProvince as $item)
                        <option value="{{ $item['id'] }}">{{ $item['name'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 mt-2">
                <select class="form-select" aria-label="Default select example" disabled id="kabupaten" onchange="handleChangeKabupaten(this.value)">
                    <option value="">Select a Kabupaten/Kota</option>
                </select>
            </div>
            <div class="col-12 mt-2">
                <select class="form-select" aria-label="Default select example" disabled id="kecamatan" onchange="handleChangeKecamatan(this.value)">
                    <option value="">Select a kecamatan</option>
                </select>
            </div>
            <div class="col-12 mt-2">
                <select class="form-select" aria-label="Default select example" disabled id="desa">
                    <option value="">Select a Desa</option>
                </select>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        const handleChangeProvince = (value) => {
            getDataRegencies(value);
            clearKabupaten();
            clearKecamatan();
            clearDesa();
        }

        const handleChangeKabupaten = (idKabupaten) =>{
            getDistrict(idKabupaten);
            clearKecamatan();
            clearDesa();
        }

        const handleChangeKecamatan = (value) => {
            getVillage(value);
            clearDesa();
        }

        const getDataRegencies = async(idProv) => {
            const kabupaten = await fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${idProv}.json`).then((response)=>{
                const regencies = response.json();
                return regencies;
            })
            const kabupatenSelect = document.getElementById("kabupaten");
            kabupatenSelect.innerHTML = '<option value="">Select a Kabupaten/Kota</option>';

            kabupaten.forEach((regency) => {
                const option = document.createElement("option");
                option.value = regency.id;
                option.textContent = regency.name;
                kabupatenSelect.appendChild(option);
            });
            const selectElement = document.getElementById('kabupaten');
            selectElement.removeAttribute('disabled'); 
        }
        const getDistrict = (idKabupaten) =>{
            fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/districts/${idKabupaten}.json`).then(async(response)=>{
                const regencies = await response.json();
                const kabupatenSelect = document.getElementById("kecamatan");
                kabupatenSelect.innerHTML = '<option value="">Select a kecamatan</option>';

                regencies.forEach((regency) => {
                    const option = document.createElement("option");
                    option.value = regency.id;
                    option.textContent = regency.name;
                    kabupatenSelect.appendChild(option);
                });
                const selectElement = document.getElementById('kecamatan');
                selectElement.removeAttribute('disabled'); 

            })
        }
        const getVillage = (idDistrict) => {
            fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/villages/${idDistrict}.json`).then(async(response)=>{
                const regencies = await response.json();
                const kabupatenSelect = document.getElementById("desa");
                kabupatenSelect.innerHTML = '<option value="">Select a desa</option>';

                regencies.forEach((regency) => {
                    const option = document.createElement("option");
                    option.value = regency.id;
                    option.textContent = regency.name;
                    kabupatenSelect.appendChild(option);
                });
                const selectElement = document.getElementById('desa');
                selectElement.removeAttribute('disabled'); 

            })
        }
        
        const clearKabupaten = () => {
            const kabupatenSelect = document.getElementById("kabupaten");
            kabupatenSelect.innerHTML = '<option value="">Select a Kabupaten/Kota</option>';
            kabupatenSelect.setAttribute('disabled', 'disabled');
        }
        const clearKecamatan = () =>{
            const kabupatenSelect = document.getElementById("kecamatan");
            kabupatenSelect.innerHTML = '<option value="">Select a kecamatan</option>';
            kabupatenSelect.setAttribute('disabled', 'disabled');

        }
        const clearDesa = () => {
            const kabupatenSelect = document.getElementById("desa");
            kabupatenSelect.innerHTML = '<option value="">Select a desa</option>';
            kabupatenSelect.setAttribute('disabled', 'disabled');
        }
    </script>
</body>
</html>
