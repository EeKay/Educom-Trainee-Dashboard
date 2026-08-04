// test file for the graph


import { useState, useEffect } from 'react';
import React, { Component } from "react";
import Chart from "react-apexcharts";

function App (){
  const API_BASE = 'http://127.0.0.1:9000/api';
  const [rangeStats, setRangeStats] = useState([]);
  const currentUser = 1;
  const startDate = "2026-7-10";
  const endDate = "2026-7-21";
  

  useEffect(() => {
  fetch(`${API_BASE}/ai/spend/period/daily/user/${currentUser}?start_date=${startDate}&end_date=${endDate}`)
  .then((res) => res.json())
    .then((data) => {
      console.log('Fetched data:', data); // <-- add this
      setRangeStats(data);
    })
  .catch((err) => console.error('Error fetching range usage:', err));
  } , []);

  const options = {
      chart: {id: "charts"},
      xaxis: {categories: rangeStats.map(x=>x.date)}
  };
  const series = [
    {
      name: "tokens",
      data: rangeStats.map(x=>x.tokens)
    }
  ];
        
  return (
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
  );
}

export default App;