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
		location = tracker.get_slot("location")
		date = tracker.get_slot("date")
		print(f"DEBUG: location={location}, date={date}")
		import pandas as pd
		try:
			df = pd.read_csv("actions/weather_lookup.csv")
		except Exception as e:
			dispatcher.utter_message(text=f"Error reading weather data: {e}")
			return []
		if location and date:
			match = df[(df['City'].str.lower() == location.lower()) & (df['Date'] == date)]
		elif location:
			match = df[df['City'].str.lower() == location.lower()]
		elif date:
			match = df[df['Date'] == date]
		else:
			match = pd.DataFrame()
		if not match.empty:
			row = match.iloc[0]
			dispatcher.utter_message(text=f"The weather in {row['City']} on {row['Date']} was {row['Weather']} with a temperature of {row['Temperature']}°C.")
		else:
			dispatcher.utter_message(text="Sorry, I couldn't find weather data for that location and date.")
		return []
