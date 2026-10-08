<x-layout.admin title="Dashboard Petugas">
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <div><h2 class="text-lg font-semibold text-gray-800">Tugas Saya</h2><p class="text-sm text-gray-500">Fokus pada tiket yang perlu dikerjakan hari ini.</p></div>
            <a href="{{ role_route('inbox.index') }}" class="bg-prussian-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-prussian-blue-700">Buka Kotak Masuk</a>
        </div>
        <div class="grid grid-cols-2 gap-3 md:grid-cols-4">
            @foreach ([['label'=>'Tugas Aktif','value'=>$stats['active_tickets'],'color'=>'text-blue-600','icon'=>'ri-task-line'],['label'=>'Belum Dikerjakan','value'=>$stats['complaints_new']+$stats['aspirations_new'],'color'=>'text-orange-600','icon'=>'ri-time-line'],['label'=>'Diproses','value'=>$stats['complaints_processing']+$stats['aspirations_follow_up'],'color'=>'text-amber-600','icon'=>'ri-loader-line'],['label'=>'Selesai','value'=>$stats['complaints_completed'],'color'=>'text-emerald-600','icon'=>'ri-checkbox-circle-line']] as $card)
                <div class="border border-gray-200 bg-white p-4"><div class="flex items-center justify-between"><p class="text-xs font-medium uppercase tracking-wider text-gray-500">{{ $card['label'] }}</p><i class="{{ $card['icon'] }} {{ $card['color'] }} text-xl"></i></div><p class="mt-2 text-2xl font-bold {{ $card['color'] }}">{{ $card['value'] }}</p></div>
            @endforeach
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>document.addEventListener('DOMContentLoaded',function(){const c=document.getElementById('officerStatusChart');if(c)new Chart(c,{type:'doughnut',data:{labels:['Baru','Diproses','Selesai','Ditolak'],datasets:[{data:[{{ $complaintStatusData['baru']+$aspirationStatusData['baru'] }},{{ $complaintStatusData['diproses'] }},{{ $complaintStatusData['selesai'] }},{{ $complaintStatusData['ditolak'] }}],backgroundColor:['#3b82f6','#f59e0b','#10b981','#ef4444'],borderWidth:0}]},options:{responsive:true,maintainAspectRatio:false,cutout:'68%',plugins:{legend:{position:'bottom'}}}});});</script>
</x-layout.admin>
