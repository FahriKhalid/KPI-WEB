<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width"/>
    <title>Email</title>
    <style type="text/css">
        body, #bodyTable, #bodyCell{height:100% !important; margin:0; padding:0; width:100% !important;}
        table{border-collapse:collapse;}
        img, a img{border:0; outline:none; text-decoration:none;}
        h1, h2, h3, h4, h5, h6{margin:0; padding:0;}
        p{margin: 1em 0;}
        .ReadMsgBody{width:100%;} .ExternalClass{width:100%;}
        .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div{line-height:100%;}
        table, td{mso-table-lspace:0pt; mso-table-rspace:0pt;}
        #outlook a{padding:0;}
        img{-ms-interpolation-mode: bicubic;}
        body, table, td, p, a, li, blockquote{-ms-text-size-adjust:100%; -webkit-text-size-adjust:100%;}
        .flexibleContainerCell{padding-top:20px; padding-Right:20px; padding-Left:20px;}
        .flexibleImage{height:auto;}
        .bottomShim{padding-bottom:20px;}
        .imageContent, .imageContentLast{padding-bottom:20px;}
        .nestedContainerCell{padding-top:20px; padding-Right:20px; padding-Left:20px;}
        body, #bodyTable{background-color:#F5F5F5;}
        #bodyCell{padding-top:40px; padding-bottom:40px;}
        #emailBody{background-color:#FFFFFF; border:1px solid #DDDDDD; border-collapse:separate; border-radius:4px;}
        h1, h2, h3, h4, h5, h6{color:#202020; font-family:Helvetica; font-size:20px; line-height:125%; text-align:Left;}
        .textContent, .textContentLast{color:#404040; font-family:Helvetica; font-size:16px; line-height:125%; text-align:Left; padding-bottom:20px;}
        .textContent a, .textContentLast a{color:#2C9AB7; text-decoration:underline;}
        .nestedContainer{background-color:#E5E5E5; border:1px solid #CCCCCC;}
        .emailButton{background-color:#2C9AB7; border-collapse:separate; border-radius:4px;}
        .buttonContent{color:#FFFFFF; font-family:Helvetica; font-size:18px; font-weight:bold; line-height:100%; padding:15px; text-align:center;}
        .buttonContent a{color:#FFFFFF; display:block; text-decoration:none;}
        .emailCalendar{background-color:#FFFFFF; border:1px solid #CCCCCC;}
        .emailCalendarMonth{background-color:#2C9AB7; color:#FFFFFF; font-family:Helvetica, Arial, sans-serif; font-size:16px; font-weight:bold; padding-top:10px; padding-bottom:10px; text-align:center;}
        .emailCalendarDay{color:#2C9AB7; font-family:Helvetica, Arial, sans-serif; font-size:60px; font-weight:bold; line-height:100%; padding-top:20px; padding-bottom:20px; text-align:center;}
        .nomorpelaporan tr td { padding-right: 8px; padding-bottom: 5px; }
        @media  only screen and (max-width: 480px){

            body{width:100% !important; min-width:100% !important;}
            img[class="flexibleImage"]{height:auto !important; width:100% !important;}
            table[class="emailButton"]{width:100% !important;}
            td[class="buttonContent"]{padding:0 !important;}
            td[class="buttonContent"] a{padding:15px !important;}

            td[class="textContentLast"], td[class="imageContentLast"]{padding-top:20px !important;}
            td[id="bodyCell"]{padding-top:10px !important; padding-Right:10px !important; padding-Left:10px !important;}
        }
    </style>
</head>
<body>
<center>
    <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable">
        <tr>
            <td align="center" valign="top" id="bodyCell">
                <table border="0" cellpadding="0" cellspacing="0" width="600" id="emailBody">
                    <tr>
                        <td align="center" valign="top">

                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td align="center" valign="top">
                                        <table border="0" cellpadding="0" cellspacing="0" width="600" class="flexibleContainer">
                                            <tr>
                                                <td align="center" valign="top" width="600" class="flexibleContainerCell">
                                                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                        <tr>
                                                            <td valign="top" class="textContent">
                                                                <h3>Kepada Yth. {{ $pengaduan->boss->name }}</h3>
                                                                <p>Dalam rangka penerapan Pengendalian Gratifikasi PT Pupuk Kalimantan Timur, bersama ini kami sampaikan Laporan gratifikasi yang dilaporkan Karyawan dibawah Unit Kerja Bapak/Ibu melalui Gratifikasi Online (GRANOL) PT Pupuk Kaltim yang wajib Bapak/Ibu konfirmasi, dengan perincian </p>
                                                                <table class="nomorpelaporan">
                                                                    <tr>
                                                                        <td>Nomor Laporan</td>
                                                                        <td>:</td>
                                                                        <td>{{ $pengaduan->code }} </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Nama Pelapor</td>
                                                                        <td>:</td>
                                                                        <td>{{ $pengaduan->user->name }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Tanggal Input Pelaporan</td>
                                                                        <td>:</td>
                                                                        <td>{{ $pengaduan->reported_date }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Jenis Pelaporan</td>
                                                                        <td>:</td>
                                                                        <td>
                                                                            @if ($pengaduan->type == 1)
                                                                                Pelaporan Penerimaan Hadiah
                                                                            @elseif ($pengaduan->type == 2)
                                                                                Pelaporan Pemberian Hadiah
                                                                            @elseif ($pengaduan->type == 3)
                                                                                Pelaporan Permintaan Hadiah
                                                                            @else
                                                                                Pelaporan Penolakan Hadiah
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                                <p>
                                                                    Telah ditetapkan status pelaporan Gratifikasi tersebut oleh UPG Pupuk Kaltim. Untuk melihat status penetapan Pelaporan Gratifikasi tersebut dapat diakses melalui link: <a href="{{ url('atasan/review/'. $pengaduan->id) }}">Lihat Laporan</a>
                                                                </p>
                                                                <p>Informasi lebih lanjut dapat menghubungi UPG Pupuk Kaltim di nomor telepon (0548) 41202, 41203 ext. 5177/5184.<br>Demikian disampaikan, atas perhatian dan kerjasama Bapak/Ibu diucapkan terima kasih.<p>

                                                                    <br>
                                                                <p style="text-align: right; font-weight: bold;">
                                                                    <img src='<?php echo $message->embed(public_path()."/assets/logo_upg.png"); ?>' class="imgresponsive img-kontak" style="max-width: 150px;">
                                                                </p>

                                                                <strong>* email notifikasi ini dikeluarkan secara  otomatis dari system tidak untuk dibalas</strong>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                        <!-- // FLEXIBLE CONTAINER -->
                                    </td>
                                </tr>
                            </table>
                            <!-- // CENTERING TABLE -->
                        </td>
                    </tr>
                    <!-- // MODULE ROW -->


                    <!-- MODULE ROW // -->
                    <tr style="display: none;">
                        <td align="center" valign="top">
                            <!-- CENTERING TABLE // -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td align="center" valign="top">
                                        <!-- FLEXIBLE CONTAINER // -->
                                        <table border="0" cellpadding="0" cellspacing="0" width="600" class="flexibleContainer">
                                            <tr>
                                                <td valign="top" width="600" class="flexibleContainerCell">


                                                    <!-- CONTENT TABLE // -->
                                                    <!--
                                                        In multi-column content blocks, the
                                                        content tables are given set widths
                                                        and the flexibleContainer class.
                                                    -->
                                                    <table align="Left" border="0" cellpadding="0" cellspacing="0" width="260" class="flexibleContainer">
                                                        <tr>
                                                            <td valign="top" class="textContent">

                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <!-- // CONTENT TABLE -->


                                                    <!-- CONTENT TABLE // -->
                                                    <table align="Right" border="0" cellpadding="0" cellspacing="0" width="260" class="flexibleContainer">
                                                        <tr>
                                                            <td valign="top" class="textContentLast">
                                                                <h3>Right Column</h3>
                                                                <br />
                                                                A kitten or kitty is a juvenile domesticated cat. A feline litter usually consists of two to five kittens. To survive, kittens need the care of their mother for the first several weeks of their life. Kittens are highly social animals and spend most of their waking hours playing and interacting with available companions.
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <!-- // CONTENT TABLE -->


                                                </td>
                                            </tr>
                                        </table>
                                        <!-- // FLEXIBLE CONTAINER -->
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</center>
</body>
</html>