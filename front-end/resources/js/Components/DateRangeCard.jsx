import { useState, useEffect } from 'react';
import '../../css/date-range-card.css';
import React, { Component } from "react";
import Chart from "react-apexcharts";


export default function DateRangeCard({ rangeStats, onRangeChange, currentUser, maxDate }) {
  
  function formatDate(date) {
    return date.toISOString().split('T')[0];
  }

  // Create default month range once
  const defaultStart = new Date();
  defaultStart.setMonth(defaultStart.getMonth() - 1);

  const [startDate, setStartDate] = useState(
    formatDate(defaultStart)
  );

  const [endDate, setEndDate] = useState(
    formatDate(new Date())
  );


  // Send default range to API
  useEffect(() => {
    onRangeChange(
      formatDate(defaultStart),
      formatDate(new Date())
    );
  }, []);

    function handleStartChange(e) {
    const newStart = e.target.value;
    setStartDate(newStart);
    onRangeChange(newStart, endDate);
  }

  function handleEndChange(e) {
    const newEnd = e.target.value;
    setEndDate(newEnd);
    onRangeChange(startDate, newEnd);
  }

  //data for the graph
  const options = {
    chart: {
      id: 'charts',
      toolbar: { show: false },
    },
    colors: ['#F24452', '#3DAEDA'],
    stroke: {
      curve: 'smooth',
      width: 3,
    },
    xaxis: {
      categories: rangeStats.map((x) => x.date),
      tickAmount: 5,
    },
    yaxis: [
      {
        labels: {
          show: false,
        },
      },
      {
        opposite: true,
        labels: {
          show: false,
        },
      },
    ],
    grid: {
      borderColor: '#eee',
    },
    tooltip: {
      y: [
        {
          formatter: (value) => `${Math.round(value)} tokens`,
        },
        {
          formatter: (value) => `€${value.toFixed(3)}`,
        },
      ],
    },
  };
  const series = [
    {
      name: "tokens",
      data: rangeStats.map(x=>x.tokens)
    },
    {
      name: "spend",
      data: rangeStats.map(x=>x.spend)
    },
  ];


  return (
    <div className="date-range-card">

      <div className="date-range-fields">
        <div className="date-range-row">
          <span className="date-range-text">
            Start:
          </span>
          <input
            type="date"
            value={startDate}
            max={maxDate}
            onChange={handleStartChange}
            className="date-range-input"
          />
        </div>
        <div className="date-range-row">
          <span className="date-range-text">
            End:
          </span>
          <input
            type="date"
            value={endDate}
            max={maxDate}
            onChange={handleEndChange}
            className="date-range-input"
          />
        </div>
      </div>

      <div className="date-range-chart">
          <div className="app">
            <div className="row">
              <div className="mixed-chart">
                <Chart
                    options={options}
                    series={series}
                    type="line"
                    width="500"
                />
              </div>
            </div>
          </div>
      </div>

    </div>
  );
}