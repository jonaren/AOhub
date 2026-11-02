
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
</head>
<body>
    <div class="container my-5">
        <h1 class="text-center mb-5">Standards of Excellence Awards</h1>
        
        <div id="loading" class="loading">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Loading organizations data...</p>
        </div>
        
        <div id="error" class="error d-none">
            <strong>Error:</strong> Unable to load data from Google Sheet. Please check the URL and try again.
        </div>
        
        <div id="content" class="d-none">
            <!-- Dynamic content will be inserted here -->
        </div>
    </div>
</div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        // Google Sheet CSV URL
        const SHEET_URL = 'https://docs.google.com/spreadsheets/d/e/2PACX-1vTU5DSLAsDWMq-WxOOhbseV8RCKR5iWzi7HgCZHyOYHtQQ51H_vDux6xfRj38IQWHQHwzGZ3_K7TImx/pub?output=csv';

        // Parse a single CSV line respecting quoted fields
        function parseCSVLine(line) {
            const values = [];
            let current = '';
            let inQuotes = false;
            for (let i = 0; i < line.length; i++) {
                const ch = line[i];
                if (inQuotes) {
                    if (ch === '"' && line[i + 1] === '"') {
                        current += '"';
                        i++;
                    } else if (ch === '"') {
                        inQuotes = false;
                    } else {
                        current += ch;
                    }
                } else {
                    if (ch === '"') {
                        inQuotes = true;
                    } else if (ch === ',') {
                        values.push(current.trim());
                        current = '';
                    } else {
                        current += ch;
                    }
                }
            }
            values.push(current.trim());
            return values;
        }

        // Parse CSV data
        function parseCSV(csvText) {
            const lines = csvText.trim().split('\n');
            const headers = parseCSVLine(lines[0]);

            const data = [];
            for (let i = 1; i < lines.length; i++) {
                const values = parseCSVLine(lines[i]);
                if (values.length === headers.length) {
                    const row = {};
                    headers.forEach((header, index) => {
                        row[header] = values[index];
                    });
                    data.push(row);
                }
            }
            return data;
        }

        // Get organization type CSS class
        function getOrgTypeClass(orgType) {
            if (orgType.includes('Advisory')) return 'advisory';
            if (orgType.includes('Echelon')) return 'echelon';
            if (orgType.includes('Auxiliary')) return 'auxiliary';
            return 'advisory'; // default
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
                
                if (!year || !level || !orgType || !name) return;
                
                if (!organized[year]) {
                    organized[year] = [];
                }
                
                organized[year].push({
                    name: name,
                    level: level,
                    orgType: orgType,
                    division: division
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
                             <div class="org-type-tag ${orgTypeClass}">
                                ${org.orgType}
                            </div>
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
            
            // Handle 2025 first if present
            if (years.includes('2026')) {
                const organizations = organizedData['2026'];
                html += `<div class="year-section">`;
                html += `<h2 class="mb-4">2026</h2>`;
                html += createOrganizationCards(organizations);
                html += `</div>`; // Close year-section
            }
            
            // Create accordion for 2024 and 2023
            const accordionYears = ['2025','2024', '2023'];
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
            const otherYears = years.filter(year => !accordionYears.includes(year) && year !== '2026');
            
            otherYears.forEach(year => {
                const organizations = organizedData[year];
                
                html += `<div class="year-section">`;
                html += `<h2 class="mb-4">${year}</h2>`;
                html += createOrganizationCards(organizations);
                html += `</div>`; // Close year-section
            });
            
            return html;
        }

        // Fetch and display data
        async function loadData() {
            try {
                const response = await fetch(SHEET_URL);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const csvText = await response.text();
                const data = parseCSV(csvText);
                const organizedData = organizeData(data);
                const html = createHTML(organizedData);
                
                // Hide loading, show content
                document.getElementById('loading').classList.add('d-none');
                document.getElementById('content').classList.remove('d-none');
                document.getElementById('content').innerHTML = html;
                
            } catch (error) {
                console.error('Error loading data:', error);
                document.getElementById('loading').classList.add('d-none');
                document.getElementById('error').classList.remove('d-none');
            }
        }

        // Load data when page loads
        document.addEventListener('DOMContentLoaded', loadData);
    </script>
