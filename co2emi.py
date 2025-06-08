def get_meal_emission(meal_type, weight_kg):
    factors = {
        "beef": 27.0,
        "cheese": 13.5,
        "chicken": 6.9,
        "rice": 2.7,
        "vegetables": 2.0,
        "lentils": 0.9,
    }
    factor = factors.get(meal_type.lower(), 2.0)
    return weight_kg * factor

def get_electricity_emission(kwh, source = 'average'):
    source_factors = {
        "coal": 0.9,
        "natural_gas": 0.4,
        "renewable": 0.05,
        "average": 0.5,
    }
    factor = source_factors.get(source.lower(), 0.5)
    return kwh * factor

def get_vehicle_emission(km, vehicle_type='petrol'):
    vehicle_factor = {
        "petrol": 0.192,
        "diesel": 0.171,
        "electric": 0.06,
        "bus": 0.105,
        "train": 0.041
    }
    factor = vehicle_factor.get(vehicle_type.lower(), 0.192)
    return km * factor

def get_plastic_emission(weight_kg):
    return weight_kg * 6.0

def main():
    print("=== CO₂ Emission Calculator ===")

    # Meal input
    meal_type = input("Enter meal type (beef, cheese, chicken, rice, vegetables, lentils): ")
    meal_weight = float(input("Enter meal weight in kg: "))
    meal_emission = get_meal_emission(meal_type, meal_weight)

    # Electrictiy input
    electricity = float(input("Enter electricity used (in kWh): "))
    electricity_emission = get_electricity_emission(electricity)

    # Vehicle input
    vehicle_type = input("Enter vehicle type (petrol, diesel, electric, bus, train): ")
    distance = float(input("Enter distance traveled (in km): "))
    vehicle_emission = get_vehicle_emission(distance, vehicle_type)

    # Plastic input
    plastic_weight = float(input("Enter plastic used (in kg): "))
    plastic_emission = get_plastic_emission(plastic_weight)

    # Total
    total_emission = meal_emission + electricity_emission + vehicle_emission + plastic_emission

    print("\n=== CO₂ Emission Report ===")
    print(f"Meal CO₂: {meal_emission:.2f} kg")
    print(f"Electricity CO₂: {electricity_emission:.2f} kg")
    print(f"Vehicle CO₂: {vehicle_emission:.2f} kg")
    print(f"Plastic CO₂: {plastic_emission:.2f} kg")
    print(f"-----------------------------------")
    print(f"Total CO₂: {total_emission:.2f} kg")

if __name__ == "__main__":
    main()