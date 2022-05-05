package com.bk.lrandom.droidmarket.models;

import android.os.Parcel;
import android.os.Parcelable;

public class Products implements Parcelable {
	public static final String TAG_ID = "id";
	public static final String TAG_TITLE = "title";
	public static final String TAG_CATEGORIES_NAME = "categories_name";
	public static final String TAG_CATEGORIES_ID = "categories_id";
	public static final String TAG_COUNTY_NAME = "county_name";
	public static final String TAG_COUNTY_ID = "county_id";
	public static final String TAG_FULL_NAME = "user_name";
	public static final String TAG_DATE_POST = "updated_at";
	public static final String TAG_IMAGES = "image_path";
	public static final String TAG_PRICE = "price";
	public static final String TAG_CONTENT = "content";
	public static final String TAG_CURRENCY = "currency";
	public static final String TAG_CITIES = "cities_name";

	int id;
	String title;
	String price;
	String images_path;
	String provinces;
	int county_id;
	String categories;
	int categories_id;
	int user_id;
	String user_name;
	String date_post;
	String content;
	String currency;
	String cities;

	public String getCities() {
		return cities;
	}

	public void setCities(String cities) {
		this.cities = cities;
	}

	public String getCurrency() {
		return currency;
	}

	public void setCurrency(String currency) {
		this.currency = currency;
	}

	public String getContent() {
		return content;
	}

	public void setContent(String content) {
		this.content = content;
	}

	public Products() {
		// TODO Auto-generated constructor stub
	}

	public Products(Parcel in) {
		id = in.readInt();
		title = in.readString();
		price = in.readString();
		images_path = in.readString();
		provinces = in.readString();
		county_id = in.readInt();
		categories = in.readString();
		categories_id = in.readInt();
		user_id = in.readInt();
		user_name = in.readString();
		date_post = in.readString();
		content = in.readString();
		currency = in.readString();
		cities = in.readString();
	}

	public String getProvinces() {
		return provinces;
	}

	public void setProvinces(String provinces) {
		this.provinces = provinces;
	}

	public int getcounty_id() {
		return county_id;
	}

	public void setcounty_id(int county_id) {
		this.county_id = county_id;
	}

	public String getCategories() {
		return categories;
	}

	public void setCategories(String categories) {
		this.categories = categories;
	}

	public int getCategories_id() {
		return categories_id;
	}

	public void setCategories_id(int categories_id) {
		this.categories_id = categories_id;
	}

	public int getUser_id() {
		return user_id;
	}

	public void setUser_id(int user_id) {
		this.user_id = user_id;
	}

	public String getUser_name() {
		return user_name;
	}

	public void setUser_name(String user_name) {
		this.user_name = user_name;
	}

	public String getDate_post() {
		return date_post;
	}

	public void setDate_post(String date_post) {
		this.date_post = date_post;
	}

	public int getId() {
		return id;
	}

	public void setId(int id) {
		this.id = id;
	}

	public String getTitle() {
		return title;
	}

	public void setTitle(String title) {
		this.title = title;
	}

	public String getPrice() {
		return price;
	}

	public void setPrice(String price) {
		this.price = price;
	}

	public String getImages_path() {
		return images_path;
	}

	public void setImages_path(String images_path) {
		this.images_path = images_path;
	}

	@Override
	public int describeContents() {
		// TODO Auto-generated method stub
		return 0;
	}

	@Override
	public void writeToParcel(Parcel dest, int flags) {
		// TODO Auto-generated method stub
		dest.writeInt(id);
		dest.writeString(title);
		dest.writeString(price);
		dest.writeString(images_path);
		dest.writeString(categories);
		dest.writeInt(categories_id);
		dest.writeString(provinces);
		dest.writeInt(county_id);
		dest.writeInt(user_id);
		dest.writeString(user_name);
		dest.writeString(date_post);
		dest.writeString(content);
		dest.writeString(currency);
		dest.writeString(cities);
	}

	public static final Parcelable.Creator<Products> CREATOR = new Parcelable.Creator<Products>() {
		@Override
		public Products createFromParcel(Parcel in) {
			// TODO Auto-generated method stub
			return new Products(in);
		}

		@Override
		public Products[] newArray(int size) {
			// TODO Auto-generated method stub
			return new Products[size];
		}
	};
}
