function calculatePersentaseRealisasi(target, realisasi, persentaseRealisasi)
{
    var persentase = 0.00;
    // higher better
    if(persentaseRealisasi == 1) {
        if (target == 0) {
            if (realisasi == 0) {
                persentase = 100;
            }
        } else {
            if (realisasi >= 0){
                persentase = (realisasi / target) * 100;
            } 
        }
    } else {
        if (target == 0) {
            if (realisasi == 0) {
                persentase = 100;
            }
        } else {
            if (realisasi >= 0) {
                persentase = (1 + ((target - realisasi) / target)) * 100;
                if (persentase < 0) {
                    persentase = 0;
                }
            }
        }
    }
    // return parseFloat(Math.round(persentase)).toFixed(2);
    return parseFloat(Math.ceil(persentase)).toFixed(2);
}

function convertion(persentaseRealisasi) {
    var konversi;
    if(persentaseRealisasi < 70) {
        konversi = 0;
    } else if(persentaseRealisasi >= 70 && persentaseRealisasi < 80) {
        konversi = 1;
    } else if(persentaseRealisasi >= 80 && persentaseRealisasi < 90) {
        konversi = 2;
    } else if(persentaseRealisasi >= 90 && persentaseRealisasi <= 100) {
        konversi = 3;
    } else if(persentaseRealisasi > 100) {
        konversi = 4;
    }
    return konversi;
}

function nilaiAkhir(konversi, bobot) {
    return (konversi * bobot) / 100;
}