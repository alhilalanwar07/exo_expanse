<?php

namespace App\Livewire\Admin;

use App\Models\SiswakkriHistory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Layout('components.layouts.admin')]
class SiswakkriHistoryManagement extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public string $search = '';

    #[Url(history: true)]
    public string $platformFilter = '';

    #[Url(history: true)]
    public string $statusFilter = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingPlatformFilter(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function export(string $format = 'csv'): StreamedResponse
    {
        $format = strtolower($format);

        if (! in_array($format, ['csv', 'xls'], true)) {
            $format = 'csv';
        }

        $rows = $this->getExportQuery()
            ->orderByDesc('submitted_at')
            ->get();

        if ($format === 'xls') {
            return $this->downloadAsExcelCompatible($rows);
        }

        return $this->downloadAsCsv($rows);
    }

    public function deleteHistory(int $id): void
    {
        SiswakkriHistory::query()->findOrFail($id)->delete();

        $this->dispatch('toast', message: 'Riwayat berhasil dihapus.', type: 'success');
    }

    public function render()
    {
        $historyRows = $this->getBaseQuery()
            ->orderByDesc('submitted_at')
            ->paginate(15);

        $platforms = SiswakkriHistory::query()
            ->select('social_platform')
            ->distinct()
            ->orderBy('social_platform')
            ->pluck('social_platform');

        return view('livewire.admin.siswakkri-history-management', [
            'historyRows' => $historyRows,
            'platforms' => $platforms,
            'totalRows' => SiswakkriHistory::query()->count(),
        ]);
    }

    private function getBaseQuery(bool $withStatusFilter = true): Builder
    {
        return SiswakkriHistory::query()
            ->when($this->search, function (Builder $query): void {
                $query->where(function (Builder $subQuery): void {
                    $subQuery
                        ->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('social_account', 'like', '%'.$this->search.'%')
                        ->orWhere('submitted_from_ip', 'like', '%'.$this->search.'%');
                });
            })
            ->when($this->platformFilter, function (Builder $query): void {
                $query->where('social_platform', $this->platformFilter);
            })
            ->when($withStatusFilter && $this->statusFilter !== '', function (Builder $query): void {
                $query->where('replaced_previous', $this->statusFilter === 'old');
            });
    }

    private function getExportQuery(): Builder
    {
        return $this->getBaseQuery(withStatusFilter: false)
            ->where('replaced_previous', false);
    }

    private function downloadAsCsv(Collection $rows): StreamedResponse
    {
        $headers = ['Waktu', 'Nama', 'Platform', 'Akun', 'Usia', 'Status', 'IP'];
        $fileName = 'riwayat-siswakkri-'.now()->format('Ymd-His').'.csv';

        return response()->streamDownload(function () use ($headers, $rows): void {
            $output = fopen('php://output', 'w');

            fputcsv($output, $headers);

            foreach ($rows as $row) {
                fputcsv($output, $this->transformRow($row));
            }

            fclose($output);
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    private function downloadAsExcelCompatible(Collection $rows): StreamedResponse
    {
        $headers = ['Waktu', 'Nama', 'Platform', 'Akun', 'Usia', 'Status', 'IP'];
        $fileName = 'riwayat-siswakkri-'.now()->format('Ymd-His').'.xls';

        return response()->streamDownload(function () use ($headers, $rows): void {
            $output = fopen('php://output', 'w');

            fwrite($output, implode("\t", $headers).PHP_EOL);

            foreach ($rows as $row) {
                $line = array_map(
                    fn ($value): string => str_replace(["\t", "\n", "\r"], ' ', (string) $value),
                    $this->transformRow($row)
                );

                fwrite($output, implode("\t", $line).PHP_EOL);
            }

            fclose($output);
        }, $fileName, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
        ]);
    }

    private function transformRow(SiswakkriHistory $row): array
    {
        return [
            $row->submitted_at?->format('Y-m-d H:i:s') ?? '-',
            $row->name,
            strtoupper($row->social_platform),
            $row->social_account,
            $row->age,
            $row->replaced_previous ? 'Ganti data lama' : 'Data baru',
            $row->submitted_from_ip ?? '-',
        ];
    }
}
