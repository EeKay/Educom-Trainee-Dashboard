import { useState } from 'react';
import '../../css/date-range-card.css';

export default function DateRangeCard({ onRangeChange }) {
  const [startDate, setStartDate] = useState('');
  const [endDate, setEndDate] = useState('');

  function handleStartChange(e) {
    setStartDate(e.target.value);
    if (onRangeChange) onRangeChange(e.target.value, endDate);
  }

  function handleEndChange(e) {
    setEndDate(e.target.value);
    if (onRangeChange) onRangeChange(startDate, e.target.value);
  }

  return (
    <div className="date-range-card">
      <div className="date-range-fields">
        <div className="date-range-row">
          <span className="date-range-text">Start Date:</span>
          <input type="date" value={startDate} onChange={handleStartChange} className="date-range-input" />
        </div>
        <div className="date-range-row">
          <span className="date-range-text">End Date:</span>
          <input type="date" value={endDate} onChange={handleEndChange} className="date-range-input" />
        </div>
      </div>

      <div className="date-range-result">
        <div className="date-range-result-value">150,000 tokens</div>
        <div className="date-range-result-spend">Spend: $0.19</div>
      </div>
    </div>
  );
}