# This files contains your custom actions which can be used to run
# custom Python code.
#
# See this guide on how to implement these action:
# https://rasa.com/docs/rasa/custom-actions



import pandas as pd
from rasa_sdk import Action, Tracker
from rasa_sdk.executor import CollectingDispatcher

class ActionGetWeather(Action):
	def name(self):
		return "action_get_weather"

	def run(self, dispatcher, tracker, domain):
		location = tracker.get_slot("location")
		date = tracker.get_slot("date")
		print(f"DEBUG: location={location}, date={date}")
		try:
			df = pd.read_csv("actions/weatherHistory.csv")
		except Exception as e:
			dispatcher.utter_message(text=f"Error reading weather data: {e}")
			return []
		# Try to match location in all string columns if not found in 'Summary'
		result = df
		if date:
			result = result[result['Formatted Date'].astype(str).str.contains(str(date))]
		if location:
			# Try 'Summary' first, then any string column
			loc_match = result[result['Summary'].str.contains(location, case=False, na=False)]
			if loc_match.empty:
				for col in result.select_dtypes(include='object').columns:
					loc_match = result[result[col].astype(str).str.contains(location, case=False, na=False)]
					if not loc_match.empty:
						result = loc_match
						break
			else:
				result = loc_match
		if not result.empty:
			temp = result.iloc[0]['Temperature (C)']
			dispatcher.utter_message(text=f"The temperature in {location or 'the location'} on {date or 'that date'} was {temp}°C.")
		else:
			dispatcher.utter_message(text="Sorry, I couldn't find weather data for that location and date.")
		return []
