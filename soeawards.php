
    <style>
        .organization-card {
            background-color: #f8f9fa;
            border-radius: 0.5rem;
            padding: 1.5rem;
            margin-bottom: 1rem;
            border: 1px solid #dee2e6;
            position: relative;
        }
        .year-section {
            margin-bottom: 3rem;
        }
        .level-badge {
           position: absolute;
    top: -8px;
    left: -8px;
    font-size: 1.2rem;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #dee2e6;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .level-badge.gold {
            background-color: #ffd700;
            color: #b8860b;
        }
        .level-badge.silver {
            background-color: #c0c0c0;
            color: #696969;
        }
        .org-type-tag {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.7rem;
            font-weight: 600;
            color: white;
            margin-bottom: 0.5rem;
        }
        .advisory {
            background-color: #ee3a43;
        }
        .echelon {
            background-color: #002056;
        }
        .auxiliary {
            background-color: #61769c;
        }
        .loading {
            text-align: center;
            padding: 2rem;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            padding: 1rem;
            border-radius: 0.375rem;
            margin: 1rem 0;
        }
        .organization-name {
            margin-top: 0.5rem;
            margin-bottom: 0;
            font-weight: 500;
        }
    </style>

    <div class="container my-5">
        <h1 class="text-center mb-5">Standards of Excellence Awards</h1>
        
        <div id="loading" class="loading">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Loading organizations data...</p>
        </div>
        
        <div id="error" class="error d-none">
            <strong>Error:</strong> <span id="errorMessage">Unable to load data from Google Sheets. Please try again later.</span>
        </div>
        
        <div id="content" class="d-none">
            <!-- Dynamic content will be inserted here -->
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        // =============================================
        // CONFIGURATION - UPDATE THESE VALUES
        // =============================================
        const SPREADSHEET_ID = '1T0-9eLFdOfmVc4HV9N7FCym8bbipFIitV04RQuP8mUE';
        const API_KEY = 'AIzaSyAPM5xbd3Lxk6dRx_5Kb9U2fUzdUEiwUzw'; // Replace with your actual API key
        const SHEET_RANGE = 'Sheet1!A:Z'; // Adjust range as needed
        // =============================================

        // Get organization type CSS class
        function getOrgTypeClass(orgType) {
            if (orgType && orgType.includes('Advisory')) return 'advisory';
            if (orgType && orgType.includes('Echelon')) return 'echelon';
            if (orgType && orgType.includes('Auxiliary')) return 'auxiliary';
            return 'advisory'; // default
        }

        // Process Google Sheets API response
        function processSheetData(response) {
            const values = response.values;
            if (!values || values.length === 0) {
                throw new Error('No data found in the spreadsheet');
            }

            // First row contains headers
            const headers = values[0];
            const data = [];

            // Process each row (skip header row)
            for (let i = 1; i < values.length; i++) {
                const row = values[i];
                if (row.length === 0) continue; // Skip empty rows
                
                const rowData = {};
                headers.forEach((header, index) => {
                    rowData[header] = row[index] || ''; // Handle missing values
                });
                
                // Only add rows that have required fields
                if (rowData.Year && rowData.Level && rowData.Name) {
                    data.push(rowData);
                }
            }

            return data;
        }

        // Organize data by year only
        function organizeData(data) {
            const organized = {};
            
            data.forEach(row => {
                const year = row.Year;
                const level = row.Level?.toLowerCase();
                const orgType = row['Organization Type'];
                const name = row.Name;
                const division = row.Division;
                
                if (!year || !level || !name) return;
                
                if (!organized[year]) {
                    organized[year] = [];
                }
                
                organized[year].push({
                    name: name,
                    level: level,
                    orgType: orgType || '',
                    division: division || ''
                });
            });
            
            return organized;
        }

        // Create organization cards HTML
        function createOrganizationCards(organizations) {
            // Sort organizations: gold first, then silver, then alphabetically by name
            organizations.sort((a, b) => {
                if (a.level === 'gold' && b.level === 'silver') return -1;
                if (a.level === 'silver' && b.level === 'gold') return 1;
                return a.name.localeCompare(b.name);
            });
            
            let cardsHtml = `<div class="row">`;
            organizations.forEach(org => {
                const orgTypeClass = getOrgTypeClass(org.orgType);
                const divisionHtml = org.division ? `<p class="text-muted mb-2" style="font-size: 0.8rem;"><i class="fa-solid fa-location-dot me-1"></i>${org.division}</p>` : '';
                cardsHtml += `
                    <div class="col-md-4 col-lg-3 mb-3">
                        <div class="organization-card h-100">
                            <div class="level-badge ${org.level}">
                                <i class="fa-solid fa-award"></i>
                            </div>
                           
                            <h6 class="organization-name mb-1">${org.name}</h6>
                            ${divisionHtml}
                            ${org.orgType ? `<div class="org-type-tag ${orgTypeClass}">${org.orgType}</div>` : ''}
                        </div>
                    </div>
                `;
            });
            cardsHtml += `</div>`; // Close row
            return cardsHtml;
        }

        // Create HTML content
        function createHTML(organizedData) {
            let html = '';
            
            // Sort years in descending order
            const years = Object.keys(organizedData).sort((a, b) => b.localeCompare(a));
            
            if (years.length === 0) {
                return '<p class="text-center text-muted">No data available.</p>';
            }
            
            // Handle 2025 first if present
            if (years.includes('2025')) {
                const organizations = organizedData['2025'];
                html += `<div class="year-section">`;
                html += `<h2 class="mb-4">2025</h2>`;
                html += createOrganizationCards(organizations);
                html += `</div>`; // Close year-section
            }
            
            // Create accordion for 2024 and 2023
            const accordionYears = ['2024', '2023'];
            const accordionYearsPresent = years.filter(year => accordionYears.includes(year));
            
            if (accordionYearsPresent.length > 0) {
                html += `<div class="accordion mb-4" id="recentYearsAccordion">`;
                
                accordionYearsPresent.forEach((year, index) => {
                    const organizations = organizedData[year];
                    
                    html += `
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading${year}">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse${year}" aria-expanded="false" aria-controls="collapse${year}">
                                    ${year}
                                </button>
                            </h2>
                            <div id="collapse${year}" class="accordion-collapse collapse" aria-labelledby="heading${year}" data-bs-parent="#recentYearsAccordion">
                                <div class="accordion-body">
                                    ${createOrganizationCards(organizations)}
                                </div>
                            </div>
                        </div>
                    `;
                });
                
                html += `</div>`; // Close accordion
            }
            
            // Create regular sections for other years (excluding 2025, 2024, 2023)
            const otherYears = years.filter(year => !accordionYears.includes(year) && year !== '2025');
            
            otherYears.forEach(year => {
                const organizations = organizedData[year];
                
                html += `<div class="year-section">`;
                html += `<h2 class="mb-4">${year}</h2>`;
                html += createOrganizationCards(organizations);
                html += `</div>`; // Close year-section
            });
            
            return html;
        }

        // Show error message
        function showError(message) {
            document.getElementById('loading').classList.add('d-none');
            document.getElementById('error').classList.remove('d-none');
            document.getElementById('errorMessage').textContent = message;
        }

        // Fetch and display data using Google Sheets API
        async function loadData() {
            try {
                // Validate configuration
                if (API_KEY === 'YOUR_API_KEY_HERE' || !API_KEY) {
                    throw new Error('API key not configured. Please update the API_KEY constant in the code.');
                }

                // Build the Google Sheets API URL
                const url = `https://sheets.googleapis.com/v4/spreadsheets/${SPREADSHEET_ID}/values/${SHEET_RANGE}?key=${API_KEY}`;
                
                const response = await fetch(url);
                
                if (!response.ok) {
                    if (response.status === 403) {
                        throw new Error('Access denied. Please check API key permissions and ensure the spreadsheet is accessible.');
                    } else if (response.status === 404) {
                        throw new Error('Spreadsheet not found. Please verify the spreadsheet ID and ensure it exists.');
                    } else {
                        const errorData = await response.json().catch(() => null);
                        const errorMessage = errorData?.error?.message || `${response.status} ${response.statusText}`;
                        throw new Error(`Failed to load data: ${errorMessage}`);
                    }
                }
                
                const jsonData = await response.json();
                const data = processSheetData(jsonData);
                const organizedData = organizeData(data);
                const html = createHTML(organizedData);
                
                // Hide loading, show content
                document.getElementById('loading').classList.add('d-none');
                document.getElementById('content').classList.remove('d-none');
                document.getElementById('content').innerHTML = html;
                
            } catch (error) {
                console.error('Error loading data:', error);
                showError(error.message);
            }
        }

        // Load data when page loads
        document.addEventListener('DOMContentLoaded', loadData);
    </script>