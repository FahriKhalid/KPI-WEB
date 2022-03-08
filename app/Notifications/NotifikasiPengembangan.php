<?php
/**
 * Created by PhpStorm.
 * User: SENA
 * Date: 10/23/2017
 * Time: 10:58 PM
 */

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Domain\Karyawan\Entities\Karyawan;
use App\Domain\User\Entities\User;
use App\Infrastructures\Repositories\KPI\KPIGlossariumRepository;

class NotifikasiPengembangan extends Notification
{
    use Queueable;
    /**
     * @var KPIGlossariumRepository
     */
    protected $KPIglossarium;
    /**
     * @var NotifikasiEnum
     */
    protected $notifikasiEnum;

    /**
     * @var string
     */
    protected $headerId;

    /**
     * @var string
     */
    protected $jenisKPI;

    /**
     * @var string
     */
    protected $tahunKPI;

    /**
     * @var string
     */
    protected $statusDokumen;

    /**
     * @var Karyawan
     */
    protected $atasan;

    /**
     * @var Karyawan
     */
    protected $bawahan;

    protected function init()
    {
        $this->KPIglossarium = new KPIGlossariumRepository();
        $this->notifikasiEnum = new NotifikasiEnum($this->KPIglossarium);
        //$this->channelManager = new ChannelManager(app());
    }

    /**
     * NotifikasiPengembangan constructor.
     * @param $headerId
     * @param $statusDokumen
     * @param User $atasan
     */
    public function __construct($headerId, $statusDokumen, User $atasan)
    {
        $this->init();
        $headerId = is_array($headerId)?$headerId[0]:$headerId;
        $this->headerId = $headerId;
        $this->jenisKPI = 'pengembangan';
        $this->statusDokumen = $statusDokumen;
        $_ = $this->KPIglossarium->findKaryawanById($this->jenisKPI, $this->headerId);

        $this->bawahan = $_->karyawan ;
        $this->atasan = $atasan->karyawan;

        $target = $this->KPIglossarium->findById($this->jenisKPI, $this->headerId);
        $this->tahunKPI = $target->Tahun;
    }

    /**
     * Get the notification's delivery channels
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database','mail'];
    }
    /**
     * Get the mail representation of the notification.
     *
    /* @param  mixed $notifiable
     * @return MailMessage
     * @throws \ErrorException
     */
    public function toMail($notifiable)
    {
        $idStatusDokumen=$this->statusDokumen=='unapproved'?8:9;
        try {
            return (new MailMessage)
//                ->view('vendor.notifications.email')
                ->subject('PT Pupuk Kaltim - KPI Online')
                ->greeting('Salam ,' . $this->atasan->NamaKaryawan . ' !')
                ->line('Memberitahukan ' . $this->notifikasiEnum->documentCounter('realisasi', $this->bawahan, $idStatusDokumen, $this->tahunKPI) . ' ' . $this->notifikasiEnum->{$this->jenisKPI}()->{$this->jenisKPI}->{$this->statusDokumen})
                ->action('Klik disini', url('' . $this->notifikasiEnum->{$this->jenisKPI}()->{$this->jenisKPI.'_url'}->{$this->statusDokumen}))
                ->line('Sekian, Terima Kasih');
        } catch (\ErrorException $errorException) {
            throw new \ErrorException('Gagal melakukan notifikasi email '.$errorException->getMessage());
        } catch (\Exception $exception) {
            throw new \ErrorException('Gagal melakukan notifikasi email '.$exception->getMessage());
        }
    }

    /**
     * @param $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return[
            //
            'JenisKPI' => $this->jenisKPI,
            'IDHeader' => $this->headerId,
            'Status'   => $this->statusDokumen,
            'URL'      => $this->notifikasiEnum->{$this->jenisKPI}()->{$this->jenisKPI.'_url'}->{$this->statusDokumen},
            'Bawahan'  => $this->bawahan->NPK,
            'Atasan'   => !empty($this->atasan)?$this->atasan->NPK:''
        ];
    }

    /**
     * @param $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return[

        ];
    }
}
