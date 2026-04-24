<h1>勤怠詳細</h1>

<p>出勤：{{ $attendance->clock_in }}</p>
<p>退勤：{{ $attendance->clock_out }}</p>
<p>休憩：{{ $attendance->break_time }}</p>
<p>合計：{{ $attendance->total_time }}</p>