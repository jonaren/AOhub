<div class="container">
    <div class="container" id="resources">
	<div class="container">
		<h1 class="text-center my-5">Resource Finder</h1>
		
		<div id="loadingMessage" class="text-center my-5" style="display: block;">
			<i class="fas fa-spinner fa-spin"></i> Loading resources...
		</div>
		
		<div id="errorMessage" class="alert alert-danger" style="display: none;"></div>
		
		<!-- Resource Type Selection -->
		<div id="filterSection" style="display: none;">
			<div class="row">
				<div class="col-md-4 mb-3">
					<div class="radio-group h-100">
						<strong><i class="fa-solid fa-screwdriver-wrench"></i> Select Resource Type:</strong> 
						<label><input checked="checked" name="resource" type="radio" value="all"> All</label> 
						<label><input name="resource" type="radio" value="best-practices"> Best Practices</label> 
						<label><input name="resource" type="radio" value="dev-tools"> Development Tools</label> 
						<label><input name="resource" type="radio" value="branding"> Branding and Marketing Resources</label>
					</div>
				</div>

				<div class="col-md-4 mb-3">
					<div class="radio-group h-100 ">
						<strong><i class="fa-solid fa-user"></i> Select User Role:</strong> 
						<label><input checked="checked" name="role" type="radio" value="all"> All</label> 
						<label><input name="role" type="radio" value="staff"> Staff</label> 
						<label><input name="role" type="radio" value="officer"> Officer</label> 
						<label><input name="role" type="radio" value="volunteer"> Volunteer</label>
					</div>
				</div>
				
				<div class="col-md-4 mb-3">
					<div class="search-group h-100">
						<strong><i class="fa-solid fa-magnifying-glass"></i> Search Resources:</strong> 
						<input id="search-input" placeholder="Search by document name or tags" style="width: 100%; padding: 8px; margin-top: 5px; border: 1px solid #ddd; border-radius: 4px;" type="text">
					</div>
				</div>
			</div>
		</div>

		<!-- Resource Items Container -->
		<div class="resource-wrapper" id="resource-container">
			<!-- Resources will be populated here via JavaScript -->
		</div>
	</div>
</div>

<script>
// Google Sheets API Configuration for Resources
const RESOURCES_CONFIG = {
    apiKey: 'AIzaSyAPM5xbd3Lxk6dRx_5Kb9U2fUzdUEiwUzw', // Replace with your new API key
    spreadsheetId: '1gfD5PsDnv_-LHbRpBZzyNDJZlTDLXdgaHE7uvMDVJlE', // Replace with your resources spreadsheet ID
    range: 'Sheet1!A:Z' // Adjust based on your sheet structure
};

let resourcesData = [];

// Load resources from Google Sheets API
async function loadResourcesFromSheets() {
    const apiUrl = `https://sheets.googleapis.com/v4/spreadsheets/${RESOURCES_CONFIG.spreadsheetId}/values/${RESOURCES_CONFIG.range}?key=${RESOURCES_CONFIG.apiKey}`;

    try {
        showLoading(true);
        hideError();
        
        console.log('Fetching resources from API:', apiUrl);
        
        const response = await fetch(apiUrl, {
            method: 'GET',
            headers: {
                'Accept': 'application/json'
            },
            cache: 'no-cache'
        });
        
        if (!response.ok) {
            throw new Error(`API request failed: ${response.status} ${response.statusText}`);
        }
        
        const jsonData = await response.json();
        
        if (!jsonData.values || jsonData.values.length === 0) {
            throw new Error('No data found in the resources spreadsheet');
        }
        
        console.log('Raw resources API response:', jsonData);
        
        // Store successful response as backup
        localStorage.setItem('resources_backup', JSON.stringify(jsonData));
        localStorage.setItem('resources_backup_timestamp', Date.now().toString());
        
        // Convert API response to resources format
        resourcesData = convertApiToResourcesFormat(jsonData.values);
        
        console.log('Converted resources data:', resourcesData);
        
        initializeResourceFinder();
        showLoading(false);
        
    } catch (error) {
        showLoading(false);
        console.error('Resources API Error:', error);
        
        // Try to use backup data
        const backupData = localStorage.getItem('resources_backup');
        const backupTimestamp = localStorage.getItem('resources_backup_timestamp');
        
        if (backupData && backupTimestamp) {
            console.warn('Using backup resources data due to API error:', error.message);
            try {
                const jsonData = JSON.parse(backupData);
                resourcesData = convertApiToResourcesFormat(jsonData.values);
                initializeResourceFinder();
                
                const backupDate = new Date(parseInt(backupTimestamp)).toLocaleString();
                showError(`Using cached data (last updated: ${backupDate}). ${error.message}`);
                
                return;
            } catch (backupError) {
                console.error('Backup resources data also failed:', backupError);
            }
        }
        
        showError(`Error loading resources: ${error.message}`);
        console.error('Final resources error:', error);
    }
}

// Convert Google Sheets API response to resources format
function convertApiToResourcesFormat(values) {
    if (!values || values.length === 0) return [];
    
    const headers = values[0];
    const resources = [];
    
    console.log('Resources headers found:', headers);
    
    // Expected columns: title, url, primaryTag, secondaryTags
    // Adjust these indices based on your actual sheet structure
    const titleIndex = headers.findIndex(h => h.toLowerCase().includes('title') || h.toLowerCase().includes('name'));
    const urlIndex = headers.findIndex(h => h.toLowerCase().includes('url') || h.toLowerCase().includes('link'));
    const primaryTagIndex = headers.findIndex(h => h.toLowerCase().includes('primary') || h.toLowerCase().includes('resource') || h.toLowerCase().includes('type'));
    const secondaryTagsIndex = headers.findIndex(h => h.toLowerCase().includes('secondary') || h.toLowerCase().includes('role') || h.toLowerCase().includes('tag'));
    
    for (let i = 1; i < values.length; i++) {
        if (values[i] && values[i].length > 0) {
            const title = values[i][titleIndex] || 'Untitled Resource';
            const url = values[i][urlIndex] || '#';
            const primaryTag = values[i][primaryTagIndex] || 'general';
            const secondaryTagsString = values[i][secondaryTagsIndex] || '';
            
            // Parse secondary tags - could be comma-separated or pipe-separated
            const secondaryTags = secondaryTagsString
                ? secondaryTagsString.split(/[,|;]/).map(tag => tag.trim()).filter(tag => tag !== '')
                : [];
            
            resources.push({
                id: i,
                title: title,
                url: url,
                primaryTag: primaryTag,
                secondaryTags: secondaryTags
            });
        }
    }
    
    return resources;
}

// Initialize the resource finder
function initializeResourceFinder() {
    renderResources(resourcesData);
    setupEventListeners();
    document.getElementById('filterSection').style.display = 'block';
    updateDisplay();
}

// Function to render resources
function renderResources(resources) {
    const resourceContainer = document.getElementById('resource-container');
    resourceContainer.innerHTML = '';
    
    resources.forEach(resource => {
        const resourceDiv = document.createElement('div');
        resourceDiv.className = 'resource-item';
        resourceDiv.setAttribute('data-id', resource.id);
        resourceDiv.setAttribute('data-primary-tag', resource.primaryTag);
        resourceDiv.setAttribute('data-secondary-tags', resource.secondaryTags.join(','));
        
        const tagsHTML = resource.secondaryTags.length > 0 
            ? `<div class="resource-tags">
                 <span class="tag">${resource.primaryTag}</span>
                 ${resource.secondaryTags.map(tag => `<span class="tag">${tag}</span>`).join('')}
               </div>`
            : `<div class="resource-tags"><span class="tag">${resource.primaryTag}</span></div>`;
        
        resourceDiv.innerHTML = `
            <a href="${resource.url}"><i class="fa-solid fa-square-up-right"></i> ${resource.title}</a>
            ${tagsHTML}
        `;
        
        resourceContainer.appendChild(resourceDiv);
    });
}

// Function to update display based on filters
function updateDisplay() {
    const selectedRole = document.querySelector('input[name="role"]:checked')?.value;
    const selectedResource = document.querySelector('input[name="resource"]:checked')?.value;
    const searchTerm = document.getElementById('search-input')?.value.toLowerCase().trim() || '';
    const resourceItems = document.querySelectorAll('.resource-item');
    
    resourceItems.forEach(item => {
        const itemSecondaryTags = item.getAttribute('data-secondary-tags').split(',').filter(tag => tag !== '');
        const itemPrimaryTag = item.getAttribute('data-primary-tag');
        const itemTitle = item.querySelector('a').textContent.toLowerCase();
        
        // Role matching: if "all" is selected, or if secondaryTags includes the selected role, or if secondaryTags is empty (universal)
        const matchesRole = selectedRole === 'all' || 
                           itemSecondaryTags.includes(selectedRole) || 
                           itemSecondaryTags.length === 0;
        
        const matchesResource = selectedResource === 'all' || itemPrimaryTag === selectedResource;
        
        // Search matching: check if search term appears in title, primary tag, or secondary tags
        const matchesSearch = searchTerm === '' || 
                             itemTitle.includes(searchTerm) ||
                             itemPrimaryTag.toLowerCase().includes(searchTerm) ||
                             itemSecondaryTags.some(tag => tag.toLowerCase().includes(searchTerm));
        
        if (matchesRole && matchesResource && matchesSearch) {
            item.classList.add('visible');
            item.style.display = 'block';
        } else {
            item.classList.remove('visible');
            item.style.display = 'none';
        }
    });
}

// Setup event listeners
function setupEventListeners() {
    // Add event listeners to radio buttons and search input
    document.querySelectorAll('input[name="role"], input[name="resource"]').forEach(input => {
        input.addEventListener('change', updateDisplay);
    });
    
    const searchInput = document.getElementById('search-input');
    if (searchInput) {
        searchInput.addEventListener('input', updateDisplay);
    }
}

// Utility functions
function showLoading(show) {
    const loadingEl = document.getElementById('loadingMessage');
    if (loadingEl) {
        loadingEl.style.display = show ? 'block' : 'none';
    }
}

function showError(message) {
    const errorEl = document.getElementById('errorMessage');
    if (errorEl) {
        errorEl.textContent = message;
        errorEl.style.display = 'block';
    }
}

function hideError() {
    const errorEl = document.getElementById('errorMessage');
    if (errorEl) {
        errorEl.style.display = 'none';
    }
}

// Fallback to static data if API fails completely
function loadFallbackData() {
    console.log('Loading fallback static data');
    resourcesData = [
        {
            "id": 1,
            "title": "Best Practices 2020",
            "url": "#",
            "primaryTag": "best-practices",
            "secondaryTags": []
        },
        {
            "id": 2,
            "title": "Best Practices 2016",
            "url": "#",
            "primaryTag": "best-practices",
            "secondaryTags": []
        },
        {
            "id": 3,
            "title": "Best Practices 2011",
            "url": "#",
            "primaryTag": "best-practices",
            "secondaryTags": []
        },
        {
            "id": 4,
            "title": "Manual of Advisory Organizations and Articles of Organization",
            "url": "#",
            "primaryTag": "dev-tools",
            "secondaryTags": ["officer"]
        },
        {
            "id": 5,
            "title": "Officer's Commitment in Working with Advisory Boards",
            "url": "#",
            "primaryTag": "dev-tools",
            "secondaryTags": ["officer"]
        },
        {
            "id": 6,
            "title": "The Manifesto",
            "url": "#",
            "primaryTag": "branding",
            "secondaryTags": ["officer"]
        },
        {
            "id": 7,
            "title": "Doing the Most Good Presentation Template",
            "url": "#",
            "primaryTag": "branding",
            "secondaryTags": ["volunteer"]
        },
        {
            "id": 8,
            "title": "Volunteer Best Practices Example Tile",
            "url": "#",
            "primaryTag": "best-practices",
            "secondaryTags": ["volunteer"]
        },
        {
            "id": 9,
            "title": "Staff Best Practices Example Tile",
            "url": "#",
            "primaryTag": "best-practices",
            "secondaryTags": ["staff"]
        }
    ];
    
    initializeResourceFinder();
    showLoading(false);
    showError('Using fallback data. Please check your API configuration.');
}

// Load resources on page load
document.addEventListener('DOMContentLoaded', function () {
    // Check if API is configured
    if (RESOURCES_CONFIG.apiKey === 'YOUR_NEW_API_KEY_HERE' || 
        RESOURCES_CONFIG.spreadsheetId === 'YOUR_RESOURCES_SPREADSHEET_ID_HERE') {
        console.warn('API not configured, using fallback data');
        loadFallbackData();
    } else {
        loadResourcesFromSheets();
    }
});
</script>
</div>