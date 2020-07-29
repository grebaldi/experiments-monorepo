import React from "react";
import { render } from "react-dom";
import { object, string, refinement, array, StructType } from "superstruct";
import isEmail from 'validator/lib/isEmail';
import { useForm } from "react-hook-form";
import { superstructResolver } from "@hookform/resolvers";

const BookingFormSchema = object({
	customer: object({
		gender: string(),
		givenName: string(),
		familyName: string(),
		birthDate: string(),
		email: refinement(string(), 'E-Mail Address', value => isEmail(value)),
		address: object({
			addressCountry: string(),
			addressLocality: string(),
			postalCode: string(),
			streetAddress: string()
		})
	}),
	participants: array(object({
		givenName: string(),
		familyName: string(),
		birthDate: string()
	}))
});

type BookingForm = StructType<typeof BookingFormSchema>

function Application() {
	const { register, handleSubmit, errors } = useForm({
//		resolver: superstructResolver(BookingFormSchema)
	});

	function onSubmit(data: BookingForm) {
		console.log(data);
	}

	return (
		<form onSubmit={handleSubmit(onSubmit)}>
			<h4>Customer</h4>
			<div 
				style={{
					display: 'grid',
					gap: '1em'
				}}
				>
				<div>
					<label>
						<div>Salutation</div>
						<select name="customer.gender" ref={register}>
							<option value="FEMALE">Mrs.</option>
							<option value="MALE">Mr.</option>
						</select>
					</label>
				</div>
				<div>
					<label>
						<div>First name</div>
						<input type="text" name="givenName" ref={register}/>	
					</label>
				</div>
				<div>
					<label>
						<div>Last name</div>
						<input type="text" name="customer.familyName" ref={register}/>
					</label>
				</div>
				<div>
					<label>
						<div>Date of birth</div>
						<input type="text" name="customer.birthdate" ref={register}/>
					</label>
				</div>
			</div>

			<button type="submit">Submit!</button>
		</form>
	);
}

export default function main() {
	render(
		<Application/>,
		document.getElementById('app')
	);
}

main();
