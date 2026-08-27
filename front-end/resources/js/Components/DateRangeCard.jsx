import { useState, useEffect } from 'react';
import '../../css/date-range-card.css';
import React, { Component } from "react";
import Chart from "react-apexcharts";

export default function DateRangeCard({ modelStats = [], resultStats = [], onRangeChange, maxDate, currentUser }) {

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

  const [dataType, setDataType] = useState("tokens");

  useEffect(() => {
    if (!currentUser) return;

    onRangeChange(
      formatDate(defaultStart),
      formatDate(new Date()),
      currentUser // <-- was missing
    );
  }, [currentUser]);

  function handleStartChange(e) {
    const newStart = e.target.value;
    setStartDate(newStart);
    onRangeChange(newStart, endDate, currentUser); // <-- was missing
  }

  function handleEndChange(e) {
    const newEnd = e.target.value;
    setEndDate(newEnd);
    onRangeChange(startDate, newEnd, currentUser); // <-- was missing
  }

  function handleTypeChange(e) {
    //const newType = e.target.value;
    setDataType(e.target.value);
    console.log("Data type changed to:", e.target.value);
  }

  //data for the graph
  const options = {
    chart: {
      id: 'charts',
      toolbar: { show: false },
    },
    colors: ['#F24452', '#3DAEDA', '#F2A541', '#BA68C8','#81C784', '#4DB6AC', '#7986CB', '#E57373', ],
    stroke: {
      curve: 'smooth',
      width: 3,
    },
    xaxis: {
      categories: resultStats.map((x) => x.date),
      tickAmount: 5,
    },
    yaxis: [
      {
        labels: {
          show: false,
        },
      },
    ],
    grid: {
      borderColor: '#eee',
    },
    tooltip: {
      y:{
          formatter: function(value){ 
            if(dataType == "tokens"){ 
              return `${Math.round(value)} tokens`;
            }
            else if(dataType == "spend"){
              return (value) + " euros";
            }
          } 
        },
    },
  };

  const series = [];


  if(dataType == "tokens") {
    modelStats.map((model) => {
      series.push({
        name: model.split('/').pop(),
        data: resultStats.map((x) => model in x.data ? x.data[model].tokens : 0)
      })
    });
  } else if(dataType == "spend") {
    modelStats.map((model) => {
      series.push({
        name: model.split('/').pop(),
        data: resultStats.map((x) => model in x.data ? (x.data[model].spend) : 0)
      })
    });
  }

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
        <div className="date-type-row">
            <span className="date-range-text">
              Data:
            </span>
          <input
            type="button"
            value={dataType}
            onClick={(e) => {
              if (e.target.value == "spend") {
                e.target.value = "tokens";
                handleTypeChange(e);
              } else {
                e.target.value = "spend";
                handleTypeChange(e);
              }
            }}
            style={{cursor:'pointer'}}
            className="data-type-input"
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