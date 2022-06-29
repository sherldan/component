function openModal(header, profile, tags, budget, pass, cost, exams) {
	let modal = document.querySelector("#sotoros-modal")

	let objHeader = document.querySelector("#sotoros-modal-header")
	
	let objBudgetHeader = document.querySelector("#sotoros-modal-budget-header")
	let objBudgetValue = document.querySelector("#sotoros-modal-budget")

	let objPassHeader = document.querySelector("#sotoros-modal-pass-header")
	let objPassValue = document.querySelector("#sotoros-modal-pass")

	let objCostHeader = document.querySelector("#sotoros-modal-cost-header")
	let objCostValue = document.querySelector("#sotoros-modal-cost")

	let objExamsHeader = document.querySelector("#sotoros-modal-exams-header")
	let objExamsValue = document.querySelector("#sotoros-modal-exams")
	
	let objProfileValue = document.querySelector("#sotoros-modal-profile")
	let objTypeValue = document.querySelector("#sotoros-modal-type")
	let objFormValue = document.querySelector("#sotoros-modal-form")

	objHeader.innerHTML = header
	
	let types = []
	let forms = []

	tags.forEach((tag) => {
		if (tag == "очно") forms.push(tag)
		if (tag == "заочно") forms.push(tag)
		if (tag == "очно-заочно") forms.push(tag)
		if (tag == "бюджет") types.push(tag)
		if (tag == "договор") types.push(tag)
	})

	objTypeValue.innerHTML = ""

	types.forEach((type, idx) => {
		objTypeValue.innerHTML += (parseInt(idx) == 0) ? type : (", " + type)
	})

	objFormValue.innerHTML = ""

	forms.forEach((form, idx) => {
		objFormValue.innerHTML += (parseInt(idx) == 0) ? form : (", " + form)
	})

	objBudgetValue.innerHTML = budget

	if (parseInt(pass) > 0) {
		objPassHeader.style.display = "block"
	} else {
		objPassHeader.style.display = "none"
	}

	if (parseInt(cost) > 0) {
		objCostHeader.style.display = "block"
	} else {
		objCostHeader.style.display = "none"
	}

	if (types.includes("бюджет")) {
		objBudgetHeader.style.display = "block"
	} else {
		objBudgetHeader.style.display = "none"
	}
	
	objProfileValue.innerHTML = profile

	objPassValue.innerHTML = parseInt(pass)
	objCostValue.innerHTML = cost + " ₽/год"

	objExamsValue.innerHTML = ""

	if (Array.isArray(exams)) {
		objExamsHeader.style.display = "block"

		exams.forEach((exam) => {
			objExamsValue.innerHTML += "<li>" + exam.name + " (" + exam.ball + ")</li>"; 
		})
	 
		if (!exams.length) {
			objExamsHeader.style.display = "none"
		}
	}


	modal.style.display = "block"
}

function closeModal() {
	let modal = document.querySelector("#sotoros-modal")
	modal.style.display = "none"
}

function openIMO() {
	let modal = document.querySelector("#sotoros-modal-imo")
	modal.style.display = "block"
}

function closeIMO() {
	let modal = document.querySelector("#sotoros-modal-imo")
	modal.style.display = "none"
}