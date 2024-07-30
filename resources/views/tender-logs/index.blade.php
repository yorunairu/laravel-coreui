<style>    
  .modal-dialog-top-right {
      position: absolute;
      top: 0;
      right: 0;
      margin: 0;
      width: 300px;
      height: 100%;
      max-height: 100%;
  }

  .modal-content-journey {
      height: 100%;
      overflow-y: auto;
  }

  .timeline {
      list-style: none;
      padding: 0;
  }

  .timeline-item {
      display: flex;
      flex-direction: column;
      border-left: 2px solid #007bff;
      padding-left: 10px;
      margin-bottom: 10px;
  }

  .timeline-date {
      font-weight: bold;
  }

  .timeline-content {
      margin-top: 5px;
  }
</style>
<div class="modal fade" id="tenderLogModal" tabindex="-1" aria-labelledby="tenderLogModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-top-right">
    <div class="modal-content-journey modal-content">
    </div>
  </div>
</div>