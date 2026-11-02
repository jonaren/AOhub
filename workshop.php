<div class="container-fluid" id="customHead">
  <div class="container mt-5 p-4">
    <h2 class="mb-4 text-center">Virtual Office Hours</h2>
    <div id="meeting-content" class="mb-3">Loading meeting info...</div>
  </div>
</div>

<style>
.icon-small {
  font-size: 1.2em !Important;
}
</style>

<script>
document.addEventListener("DOMContentLoaded", () => {
  console.log("✅ Script started");

  // Extract the spreadsheet ID from your original URL
  const spreadsheetId = "1014_SDPDoxSjuhKvLUxIyvhiW4VGuuwCjAvKn6aEb2o";
  const range = "Sheet1!A:Z"; // Adjust range as needed
  const apiKey = "AIzaSyAPM5xbd3Lxk6dRx_5Kb9U2fUzdUEiwUzw"; // Replace with your actual API key
  
  const sheetsUrl = `https://sheets.googleapis.com/v4/spreadsheets/${spreadsheetId}/values/${range}?key=${apiKey}`;

  function sheetsDataToJson(data) {
    if (!data.values || data.values.length === 0) {
      return [];
    }
    
    const rows = data.values;
    const headers = rows[0].map(h => h.trim());
    
    return rows.slice(1)
      .filter(row => row.length && row.some(cell => cell && cell.trim() !== ""))
      .map(row => {
        return headers.reduce((obj, header, i) => {
          obj[header] = row[i] ? row[i].trim() : "";
          return obj;
        }, {});
      });
  }

  function formatDate(dateString) {
    if (!dateString) return "";
    
    // Try to parse the date - handle various formats
    let date;
    
    // Check if it's already in a parseable format
    if (dateString.includes('/') || dateString.includes('-')) {
      date = new Date(dateString);
    } else {
      // If it's just numbers, try to parse as is
      date = new Date(dateString);
    }
    
    // Check if date is valid
    if (isNaN(date.getTime())) {
      return dateString; // Return original if can't parse
    }
    
    // Format as "Month Day, Year"
    const options = { 
      year: 'numeric', 
      month: 'long', 
      day: 'numeric' 
    };
    
    return date.toLocaleDateString('en-US', options);
  }

  function createCalendarLinks(meeting) {
    if (!meeting.Date || !meeting.Time) return null;
    
    // Parse date and time
    const meetingDate = new Date(meeting.Date);
    if (isNaN(meetingDate.getTime())) return null;
    
    // Parse time (assuming format like "2:00 PM" or "14:00")
    const timeStr = meeting.Time.replace(' EST', '').trim();
    let hour = 0, minute = 0;
    
    if (timeStr.includes(':')) {
      const [hourStr, minuteStr] = timeStr.split(':');
      hour = parseInt(hourStr);
      minute = parseInt(minuteStr.replace(/[^\d]/g, ''));
      
      if (timeStr.toLowerCase().includes('pm') && hour !== 12) {
        hour += 12;
      } else if (timeStr.toLowerCase().includes('am') && hour === 12) {
        hour = 0;
      }
    }
    
    // Set the time on the date
    meetingDate.setHours(hour, minute, 0, 0);
    
    // Create end time (assume 1 hour duration)
    const endDate = new Date(meetingDate.getTime() + 60 * 60 * 1000);
    
    // Format for different calendar services
    const formatGoogleDate = (date) => date.toISOString().replace(/[-:]/g, '').split('.')[0] + 'Z';
    const formatOutlookDate = (date) => date.toISOString();
    
    const title = encodeURIComponent(meeting.Topic || 'Virtual Office Hours');
    const description = encodeURIComponent(`${meeting.Description || ''}${meeting.MeetingLink ? '\n\nJoin Meeting: ' + meeting.MeetingLink : ''}`);
    const location = encodeURIComponent(meeting.MeetingLink || '');
    
    // Create calendar URLs
    const googleUrl = `https://calendar.google.com/calendar/render?action=TEMPLATE&text=${title}&dates=${formatGoogleDate(meetingDate)}/${formatGoogleDate(endDate)}&details=${description}&location=${location}`;
    
    const outlookUrl = `https://outlook.live.com/calendar/0/deeplink/compose?subject=${title}&startdt=${formatOutlookDate(meetingDate)}&enddt=${formatOutlookDate(endDate)}&body=${description}&location=${location}`;
    
    return { googleUrl, outlookUrl };
  }

  fetch(sheetsUrl)
    .then(res => {
      console.log("📥 Fetch status:", res.status);
      if (!res.ok) {
        throw new Error(`HTTP error! status: ${res.status}`);
      }
      return res.json();
    })
    .then(data => {
      console.log("📄 Raw Sheets data:", data);
      const meetings = sheetsDataToJson(data);
      console.log("📊 Parsed Meetings JSON:", meetings);

      if (meetings.length > 0) {
        const m = meetings[0];
        const formattedDate = formatDate(m.Date);
        const calendarLinks = createCalendarLinks(m);
        
        const calendarButtons = calendarLinks ? 
          `<div class="mt-3">
            <a href="${calendarLinks.outlookUrl}" target="_blank" class="btn btn-primary btn-sm w-100 mb-2">
              <i class="fab fa-microsoft icon-small me-2"></i>Add to Outlook
            </a>
            <a href="${calendarLinks.googleUrl}" target="_blank" class="btn btn-primary btn-sm w-100">
              <i class="fab fa-google icon-small me-2"></i>Add to Google Calendar
            </a>
          </div>` : 
          '';
        
        document.getElementById("meeting-content").innerHTML = `
          <div class="row">
            <div class="col-md-8">
              <h4 class="mb-3 text-white">
                ${m.Topic || "No Topic"}
              </h4>
              <p class="text-white">${m.Description || ""}</p>
            </div>
            <div class="col-md-4">
              <div class="bg-white p-3 text-body rounded-3 border" style="box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                <div class="mb-2 d-flex align-items-center">
                  <i class="fa-regular fa-calendar text-primary icon-small me-2 text-black"></i>
                  <span><strong>Date:</strong> ${formattedDate}</span>
                </div>
                <div class="mb-2 d-flex align-items-center">
                  <i class="fa-regular fa-clock text-primary icon-small me-2 text-black"></i>
                  <span><strong>Time:</strong> ${m.Time || ""} EST</span>
                </div>
                ${calendarButtons}
              </div>
            </div>
          </div>
        `;
      } else {
        document.getElementById("meeting-content").innerHTML = `<em>No meetings found</em>`;
      }
    })
    .catch(err => {
      console.error("❌ Fetch or API error:", err);
      document.getElementById("meeting-content").innerHTML = `<em>Error loading data: ${err.message}</em>`;
    });
});
</script>